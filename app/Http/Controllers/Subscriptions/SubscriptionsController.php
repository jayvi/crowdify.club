<?php
/**
 * Created by PhpStorm.
 * User: nathan
 * Date: 10/15/2015
 * Time: 5:27 PM
 */
namespace App\Http\Controllers\Subscriptions;

use App\PaypalAgreement;
use App\PaypalPaymentHistory;
use App\Services\Paypal\PaypalPaymentService;
use App\Services\Paypal\PaypalService;
use App\User;
use App\UserType;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use BlockIo;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Wallet;
use Illuminate\Support\Facades\Config;
use PayPal\Api\Agreement;
use PayPal\Api\Amount;
use PayPal\Api\Billing;
use PayPal\Api\BillingInfo;
use PayPal\Api\ChargeModel;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Plan;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\WebProfile;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class SubscriptionsController extends Controller implements PaypalListener
{


    private  $paypalService;
    public function __construct(Guard $auth, PaypalService $paypalService){
        parent::__construct($auth);
        $this->middleware('auth');
        $this->middleware('email.check');
       // $this->middleware('subscription.test');
        $this->paypalService = $paypalService;

    }

    public function index()
    {

        $user = User::with(array('usertype','subscription'))->find($this->auth->id());
        $url = "https://bitpay.com/api/rates";

        $json = file_get_contents($url);
        $data = json_decode($json, TRUE);

        $rate = $data[1]["rate"];
        $bitcoin = array();
        $bitcoin['cyclist'] = round(25 / $rate, 8);
        $bitcoin['driver'] = round(75 / $rate, 8);
        $bitcoin['pilot'] = round(497 / $rate, 8);
        $bitcoin['astronaut'] = round(4997 / $rate, 8);
        return $this->createView('subscriptions.index', compact('user', 'bitcoin'));
    }
    public function postPayment(PaypalPaymentService $paypalPaymentService, Request $request)
    {
        if($this->auth->user()->usertype->role == $request->plan){
            return redirect()->back()->with('error','You are already subscribed for this plan');
        }
        $returnUrl = route('subscriptions::payment::status').'?success=true&crowdify_plan='.$request->plan;
        $cancelUrl = route('subscriptions::payment::status').'?success=false';
//        if($this->paypalService->hasPlan($request->plan)){
//            $this->paypalService->setRedirectUrls($returnUrl,$cancelUrl);
//            return $this->paypalService->process($request,$this, $request->plan);
//        }else{
//            if($request->plan == 'astronaut'){
        $paypalPaymentService->setRedirectUrls($returnUrl, $cancelUrl);
        return $paypalPaymentService->processSubscription($request->plan, $this);
        //    }
        //}

       // return redirect()->back()->with('error','Failed to process. Please try again');
    }

    private function addPaymentHistory($payment, $request)
    {
        if($payment){
            try{
                PaypalPaymentHistory::create([
                    'user_id' => $this->auth->id(),
                    'payment_id' => $payment->getId(),
                    'payment_description' => $request->crowdify_plan,
                    'crowdify_plan'  => $request->crowdify_plan,
                    'state' => $payment->getState()
                ]);
            }catch(\Exception $e){

            }

        }
    }

    public function getPaymentStatus(PaypalPaymentService $paypalPaymentService, Request $request)
    {
        if($request->has('success') && $request->get('success') == 'true'){
           // if($request->get('crowdify_plan','') == 'astronaut'){
            if($payment = $paypalPaymentService->processApproval($request)){
                $userType = UserType::where('role','=',$request->crowdify_plan)->first();
                $this->auth->user()->update(['usertype_id' => $userType->id, 'payment_type' => 'paypal', 'last_payment_date' => Carbon::now()]);
                $this->addPaymentHistory($payment, $request);
                return redirect()->route('subscriptions::home')->with('success','Thank You for being a premium member');
            }else{
                return redirect()->route('subscriptions::home')->with('error','Failed to complete transaction. Please try again.');
            }
           // }
//        else{
//                if($this->paypalService->processApproval($request)){
//                    return redirect()->route('subscriptions::home')->with('success','Thank You for being a premium member');
//                }else{
//                    return redirect()->route('subscriptions::home')->with('error','Failed to complete transaction. Please try again.');
//                }
//            }
        }
        return redirect()->route('subscriptions::home');
    }

    public function bitPayment(Request $request)
    {
            $url = "https://bitpay.com/api/rates";
            $json = file_get_contents($url);
            $data = json_decode($json, TRUE);
            $rate = $data[1]["rate"];
            if($request->plan = 'cyclist'){
                $usd_price = 25;
                $level = 9;
            }
            elseif ($request->plan = 'driver'){
                $usd_price = 75;
                $level = 10;
            }
            elseif ($request->plan = 'pilot'){
                $usd_price = 497;
                $level = 11;
            }
            elseif ($request->plan = 'astronaunt'){
                $usd_price = 4997;
                $level = 12;
            }
            $bitcoin = round($usd_price / $rate, 8);

            $block_io = new BlockIo(env('BLOCKIO_APIKEY'), env('BLOCKIO_PIN'), env('BLOCKIO_VERSION'));
            $wallet = $this->auth->user()->wallet;
            $addressBalance = $block_io->get_address_balance(array('addresses' => $wallet->address));

            $addressBalance = floatval($addressBalance->data->available_balance);
            if ($addressBalance >= $bitcoin) {
                try {
                    $transferInfo = $block_io->withdraw_from_addresses(array('amounts' => strval($bitcoin), 'from_addresses' => $wallet->address, 'to_addresses' => '19ys1QqfvZgcTQvZ1f8FYCZ9QpD1q42C49', 'pin' => env('BLOCKIO_PIN'), 'priority' => 'low'));
                } catch (\Exception $e) {
                    return response()->json(array('status' => 400, 'message' => $e->getMessage()), 400);
                }
                //$this->auth->user()->usertype_id = $level;
                $this->auth->user()->update(['usertype_id' => $level, 'payment_type' => 'bitcoin','last_payment_date' => Carbon::now()]);
                return redirect()->route('subscriptions::home')->with('success', 'You are now a premium user');
            } else {
                $message = 'You must have ' . $bitcoin . 'BTC to buy this membership';
                return redirect()->route('profile::bank')->with('error', $message);
            }
    }

    public function postSuspendAgreement(){
         return $this->paypalService->suspendAgreement($this->auth->user(), $this);
    }

    public function postReactivateAgreement(){
        return $this->paypalService->reActivateAgreement($this->auth->user(), $this);

    }

    public function postCancelAgreement(){
        return $this->paypalService->cancelAgreement($this->auth->user(), $this);
    }

    public function redirectForApproval($approvalUrl){
        return redirect($approvalUrl);
    }

    public function failed($error)
    {
       return redirect()->route('subscriptions::home')->with('error',$error);
    }

    public function success($message)
    {
        return redirect()->route('subscriptions::home')->with('success',$message);

    }
}
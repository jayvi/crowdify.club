<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 10/19/15
 * Time: 7:46 PM
 */

namespace App\Services\Paypal;


use App\AffiliateEarning;
use Event;
use App\Events\UserAccountUpgraded;
use App\Http\Controllers\Subscriptions\PaypalListener;

use App\PaypalAgreement;
use App\PaypalPlan;
use App\UserType;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use PayPal\Api\Agreement;
use PayPal\Api\AgreementStateDescriptor;
use PayPal\Api\ChargeModel;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Api\Payer;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ShippingAddress;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Common\PayPalModel;
use PayPal\Rest\ApiContext;

class PaypalService
{
    private $apiContext;
    private $error;
    private $redirectUrls;
    private $auth;

    // the billing start with 1 month later, that's why the setup fee is the cost for the first month
    private $crowdifyPlans = [
        'cyclist' => [
            'amount' => 9,
            'setup_fee' => 9
        ],
        'driver' => [
            'amount' => 40,
            'setup_fee' => 40
        ],
        'pilot' => [
            'amount' => 40,
            'setup_fee' => 497
        ],
    ];

    public function hasPlan($crowdifyPlan)
    {
        return isset($this->crowdifyPlans[$crowdifyPlan]);
    }

    public function __construct(Guard $auth){

        // setup PayPal api context
        $this->auth = $auth;
        $paypal_conf = Config::get('paypal');
        $this->apiContext = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $this->apiContext->setConfig($paypal_conf['settings']);
        $this->error = '';
    }

    public function setRedirectUrls($returnUrl, $cancelUrl)
    {
        $this->redirectUrls = new RedirectUrls();
        $this->redirectUrls->setReturnUrl($returnUrl);
        $this->redirectUrls->setCancelUrl($cancelUrl);
    }

    private function getPaypalPlan($crowdifyPlan){
        return PaypalPlan::where('name','=',$crowdifyPlan)->first();;
    }

    public function createOrFindPlan($crowdifyPlan){
        // Create a new instance of Plan object

        $paypalPlan = $this->getPaypalPlan($crowdifyPlan);
        if($paypalPlan){
            $plan = Plan::get($paypalPlan->plan_id, $this->apiContext);
            if($plan){
                return $plan;
            }
        }
        $plan = new Plan();
        // # Basic Information
        // Fill up the basic information that is required for the plan
        $plan->setName($crowdifyPlan)
            ->setDescription(ucwords($crowdifyPlan).' Membership $'.$this->crowdifyPlans[$crowdifyPlan]['amount'].' Per Month.')
            ->setType('INFINITE');

        // # Payment definitions for this billing plan.
        $paymentDefinition = new PaymentDefinition();

// The possible values for such setters are mentioned in the setter method documentation.
// Just open the class file. e.g. lib/PayPal/Api/PaymentDefinition.php and look for setFrequency method.
// You should be able to see the acceptable values in the comments.
        $paymentDefinition->setName('Regular Payments')
            ->setType('REGULAR')
            ->setFrequency('Month')
            ->setFrequencyInterval("1")
            ->setCycles("0")
            ->setAmount(new Currency(array('value' => $this->crowdifyPlans[$crowdifyPlan]['amount'], 'currency' => 'USD')));

// Charge Models
        $chargeModel = new ChargeModel();
        $chargeModel->setType('SHIPPING')
            ->setAmount(new Currency(array('value' => 0, 'currency' => 'USD')));

        $paymentDefinition->setChargeModels(array($chargeModel));

        $merchantPreferences = new MerchantPreferences();
// ReturnURL and CancelURL are not required and used when creating billing agreement with payment_method as "credit_card".
// However, it is generally a good idea to set these values, in case you plan to create billing agreements which accepts "paypal" as payment_method.
// This will keep your plan compatible with both the possible scenarios on how it is being used in agreement.
        $merchantPreferences->setReturnUrl($this->redirectUrls->getReturnUrl())
            ->setCancelUrl($this->redirectUrls->getCancelUrl())
            ->setAutoBillAmount("yes")
            ->setInitialFailAmountAction("CANCEL")
            ->setMaxFailAttempts("1")
            ->setSetupFee(new Currency(array('value' => $this->crowdifyPlans[$crowdifyPlan]['setup_fee'], 'currency' => 'USD')));


        $plan->setPaymentDefinitions(array($paymentDefinition));
        $plan->setMerchantPreferences($merchantPreferences);


// ### Create Plan
        try {
            $plan = $plan->create($this->apiContext);

            $this->createCrowdifyPlan($plan);

        } catch (Exception $ex) {
            $this->error = $ex->getMessage();
            return false;
            exit(1);
        }

        return $plan;
    }

    public function updatePlan(Plan $createdPlan){
        if(!$createdPlan){
            return false;
        }
        if($createdPlan->getState() == 'ACTIVE'){
            return $createdPlan;
        }
        try {
            $patch = new Patch();

            $value = new PayPalModel('{
                   "state":"ACTIVE"
                 }');

            $patch->setOp('replace')
                ->setPath('/')
                ->setValue($value);
            $patchRequest = new PatchRequest();
            $patchRequest->addPatch($patch);

            $createdPlan->update($patchRequest, $this->apiContext);

            $plan = Plan::get($createdPlan->getId(), $this->apiContext);
            $paypalPlan = PaypalPlan::where('plan_id','=',$plan->getId())->first();
            if($paypalPlan){
                $paypalPlan->state = $plan->getState();
                $paypalPlan->update();
            }
            return $plan;

        } catch (Exception $ex) {
            $this->error = $ex->getMessage();
            Log::error('paypal plan update error'.$this->error);
            return false;
        }
    }

    public function createAgreement(Plan $updatedPlan){
        if(!$updatedPlan){
            return false;
        }
        $agreement = new Agreement();
        $agreement->setName($this->auth->user()->username)
            ->setDescription($updatedPlan->getDescription())
            ->setStartDate(Carbon::now()->addMonth(1)->toIso8601String());

        // Add Plan ID
        // Please note that the plan Id should be only set in this case.
        $plan = new Plan();
        $plan->setId($updatedPlan->getId());
        $agreement->setPlan($plan);

        // Add Payer
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $agreement->setPayer($payer);

        // Add Shipping Address
//        $shippingAddress = new ShippingAddress();
//        $shippingAddress->setLine1('111 First Street')
//            ->setCity('Saratoga')
//            ->setState('CA')
//            ->setPostalCode('95070')
//            ->setCountryCode('US');
//        $agreement->setShippingAddress($shippingAddress);

        // ### Create Agreement
        try {
            // Please note that as the agreement has not yet activated, we wont be receiving the ID just yet.
            $agreement = $agreement->create($this->apiContext);

            // ### Get redirect url
            // The API response provides the url that you must redirect
            // the buyer to. Retrieve the url from the $agreement->getApprovalLink()
            // method
            $approvalUrl = $agreement->getApprovalLink();
            return $approvalUrl;

        } catch (Exception $ex) {
            $this->error = $ex->getMessage();
            Log::error($this->error);
            return false;
            exit(1);
        }
    }

    private function getCrowdifyAgreement($user){
        return PaypalAgreement::where('user_id','=',$user->id)->first();
    }

    public function suspendAgreement($user, PaypalListener $listener){
        $payPalAgreement = $this->getCrowdifyAgreement($user);
        if(!$payPalAgreement){
            $this->error = 'Sorry, You are not subscribed';
            return $listener->failed($this->error);
        }

        if($payPalAgreement->state != 'Active' && $payPalAgreement->state != 'Pending'){
            $this->error = "Sorry, You don't have Active subscription";
            return $listener->failed($this->error);
        }

        $agreementStateDescriptor = new AgreementStateDescriptor();
        $agreementStateDescriptor->setNote("Suspending the agreement");

        try {

            $agreement = Agreement::get($payPalAgreement->agreement_id, $this->apiContext);
            if($agreement){
                $agreement->suspend($agreementStateDescriptor, $this->apiContext);
            }
            // Lets get the updated Agreement Object
            $agreement = Agreement::get($agreement->getId(), $this->apiContext);


            $this->updateAggrement($payPalAgreement, $agreement->getState());
            // update user
            $this->updateUser($user, 'Free');
            return $listener->success('Your subscription is successfully suspended');

        } catch (Exception $ex) {
            return $ex->getCode();
           return $listener->failed('Un-known Error');
        }
    }

    public function reActivateAgreement($user, PaypalListener $listener){
        $payPalAgreement = $this->getCrowdifyAgreement($user);
        if(!$payPalAgreement){
            $this->error = 'Sorry, You are not subscribed';
            return $listener->failed($this->error);
        }
        if($payPalAgreement->state != 'Suspended'){
            $this->error = "Sorry, You don't have suspended subscription";
            return $listener->failed($this->error);
        }
        //Create an Agreement State Descriptor, explaining the reason to suspend.
        $agreementStateDescriptor = new AgreementStateDescriptor();
        $agreementStateDescriptor->setNote("Reactivating the agreement");
        try {
            $suspendedAgreement = Agreement::get($payPalAgreement->agreement_id, $this->apiContext);
            if($suspendedAgreement){
                $suspendedAgreement->reActivate($agreementStateDescriptor, $this->apiContext);
            }
            $agreement = Agreement::get($suspendedAgreement->getId(), $this->apiContext);

            $this->updateAggrement($payPalAgreement, $agreement->getState());
            // update user
            $this->updateUser($user, 'Paid');

            return $listener->success('Your subscription is successfully Reactivated');

        } catch (Exception $ex) {
            return $ex->getMessage();
            return $listener->failed('Un-known Error');
        }
    }

    public function cancelAgreement($user, PaypalListener $listener){
        $payPalAgreement = $this->getCrowdifyAgreement($user);
        if(!$payPalAgreement){
            $this->error = 'Sorry, You are not subscribed';
            return $listener->failed($this->error);
        }
        $agreementStateDescriptor = new AgreementStateDescriptor();
        $agreementStateDescriptor->setNote("Cancelling the agreement");
        try {
            $cancelledAgreement = Agreement::get($payPalAgreement->agreement_id, $this->apiContext);
            if($cancelledAgreement){
                $cancelledAgreement->cancel($agreementStateDescriptor, $this->apiContext);
            }
            $payPalAgreement->delete();
            // update user
            $this->updateUser($user, 'Free');

            return $listener->success('Your subscription is successfully Cancelled');

        } catch (Exception $ex) {
            return $listener->failed('Un-known Error');
        }
    }


    public function optOutAgreement($user){
        $payPalAgreement = $this->getCrowdifyAgreement($user);
        if($payPalAgreement){
            try {
                $agreementStateDescriptor = new AgreementStateDescriptor();
                $agreementStateDescriptor->setNote("Cancelling the agreement");
                $cancelledAgreement = Agreement::get($payPalAgreement->agreement_id, $this->apiContext);
                if($cancelledAgreement){
                    $cancelledAgreement->cancel($agreementStateDescriptor, $this->apiContext);
                }
                $payPalAgreement->delete();
                // update user
                $this->updateUser($user, 'Free');

                return true;

            } catch (Exception $ex) {
                return false;
            }
        }

        return true;

    }

    private function updateUser( $user,$role){
        $userType = UserType::where('role','=',$role)->first();
        if($userType){
            if(!$user->isAdmin()){
                $user->usertype()->associate($userType);
                $user->update();
            }

        }
    }


    private function updateAggrement($payPalAgreement, $state)
    {
        $payPalAgreement->state = $state;
        $payPalAgreement->update();
    }

    public function process(Request $request,PaypalListener $listener, $crowdifyPlan)
    {

        $plan = $this->createOrFindPlan($crowdifyPlan);
        if($plan){
            $request->session()->put('plan_id',$plan->getId());
            $redirectUrl = $this->createAgreement($this->updatePlan($plan));
            if($redirectUrl){
                return $listener->redirectForApproval($redirectUrl);
            }
        }
        return $listener->failed($this->error);
    }

    public function executeAgreement($token){
        $agreement = new Agreement();
        try {
            // ## Execute Agreement
            // Execute the agreement by passing in the token
            $result = $agreement->execute($token, $this->apiContext);
            return $result;
        } catch (Exception $ex) {
            return false;
        }
    }

    private function cancelPreviousAgreement($payPalAgreement){
        try {
            $agreementStateDescriptor = new AgreementStateDescriptor();
            $agreementStateDescriptor->setNote("Cancelling the agreement");
            $cancelledAgreement = Agreement::get($payPalAgreement->agreement_id, $this->apiContext);
            if($cancelledAgreement){
                $cancelledAgreement->cancel($agreementStateDescriptor, $this->apiContext);
            }
            $payPalAgreement->delete();
            return true;

        } catch (Exception $ex) {
            return false;
        }
    }

    public function processApproval(Request $request){

        $token = $request->get('token');
        $previousAgreement = $this->auth->user()->subscription;
        $result = $this->executeAgreement($token);
        if($result){
            if($previousAgreement){
                $this->cancelPreviousAgreement($previousAgreement);
            }
            $agreement = Agreement::get($result->id, $this->apiContext);
            $this->createCrowdifyAgreement($agreement, $request->session()->get('plan_id','0'));
            $request->session()->forget('plan_id');
            $userType = UserType::where('role','=', $request->crowdify_plan)->first();
            $this->auth->user()->usertype()->associate($userType);
            $this->auth->user()->update();
            Event::fire(new UserAccountUpgraded($this->auth->user()));


            return true;
        }


        return false;

    }

    public function listPlans(){
        $plans = Plan::all(array('status' => 'ACTIVE'), $this->apiContext);
        return $plans;
    }

    private function createCrowdifyPlan(Plan $plan)
    {
        $paypalPlan = new PaypalPlan();
        $paypalPlan->plan_id = $plan->getId();
        $paypalPlan->name = $plan->getName();
        $paypalPlan->description = $plan->getDescription();
        $paypalPlan->type = $plan->getType();
        $paypalPlan->state = $plan->getState();
        $paypalPlan->save();
    }

    private function createCrowdifyAgreement(Agreement $agreement, $plan_id){
        $paypalAgreement = new PaypalAgreement();
        $paypalAgreement->agreement_id = $agreement->getId();
        $paypalAgreement->state = $agreement->getState();
        $paypalAgreement->name = $agreement->getName();
        $paypalAgreement->description = $agreement->getDescription();
        $paypalAgreement->plan_id = $plan_id;
        $paypalAgreement->user_id = $this->auth->id();
        $paypalAgreement->email = $this->auth->user()->email;
        $paypalAgreement->save();
    }



}
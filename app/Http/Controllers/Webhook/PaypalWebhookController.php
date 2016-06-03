<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 10/20/15
 * Time: 12:54 PM
 */

namespace App\Http\Controllers\Webhook;


use App\Http\Controllers\Controller;
use App\Services\Paypal\IpnListener;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaypalWebhookController extends Controller
{

    /**
     * as paypal currently doesn't support webhook for rest api billing agreement, we are using IPN (instant payment notification for now)
     * this function will recieve notifications from paypal for any subscription event
     *
     * @param Request $request
     */
    public function index(Request $request){
        Log::info('PaypalWebhookController');
        Log::info(json_encode($request->all()));
        $listener = new IpnListener();
        $listener->use_sandbox = env('USE_SANDBOX',false);
        $listener->debug = env('PAYPAL_DEBUG',true);

        try {
            $verified = $listener->processIpnNew();
        } catch (\Exception $e) {
            return Log::error($e->getMessage());
        }

        if ($verified) {
            Log::info('verified');
            $data = $request->all();

            Log::info(json_encode($data));

//            $user_id = json_decode($data['custom'])->user_id;
//
//            $subscription = ($data['mc_gross_1'] == '10') ? 2 : 1;
//
//            $txn = array(
//                'txn_id'       => $data['txn_id'],
//                'user_id'      => $user_id,
//                'paypal_id'    => $data['subscr_id'],
//                'subscription' => $subscription,
//                'expires'      => date('Y-m-d H:i:s', strtotime('+1 Month')),
//            );
//
//            Payment::create($txn);

        } else {
            Log::error('Transaction not verified');
        }
    }
}
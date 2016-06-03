<?php
/**
 * Created by PhpStorm.
 * User: Arifuzzaman
 * Date: 5/11/2016
 * Time: 12:02 PM
 */

namespace App\Services\Paypal;


use App\EventTicket;
use App\Http\Controllers\Subscriptions\PaypalListener;
use Exception;
use Illuminate\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PaypalPaymentService
{

    private $crowdifyPlans = [
        'cyclist' => [
            'amount' => 25,
        ],
        'driver' => [
            'amount' => 75,
        ],
        'pilot' => [
            'amount' => 497,
        ],
        'astronaut' => [
            'amount' => 4997,
        ],

    ];

    private $apiContext;
    private $error;
    private $redirectUrls;
    private $auth;
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

    public function processEventTicketSell(EventTicket $eventTicket, $amount, PaypalListener $listener)
    {
        try{
            $payment = $this->createPaymentUsingPaypal($eventTicket->name, $eventTicket->price, $amount);
            $approvalUrl = $payment->getApprovalLink();
            return $listener->redirectForApproval($approvalUrl);
        }catch(Exception $e){
            return $listener->failed('Failed to create payment. Please try again');
        }
    }
    public function processSubscription( $subscriptionName, PaypalListener $listener)
    {
        try{
            $payment = $this->createPaymentUsingPaypal($subscriptionName, $this->crowdifyPlans[$subscriptionName]['amount'], 1);
            $approvalUrl = $payment->getApprovalLink();
            return $listener->redirectForApproval($approvalUrl);
        }catch(Exception $e){
            Log::error($e->getMessage());
            return $listener->failed('Failed to create payment. Please try again');
        }
    }

    public function createPaymentUsingPaypal($name, $price ,$quantity)
    {
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

// ### Itemized information
// (Optional) Lets you specify item wise
// information
        $item1 = new Item();
        $item1->setName($name)
            ->setCurrency('USD')
            ->setQuantity($quantity)
            //->setSku("123123") // Similar to `item_number` in Classic API
            ->setPrice($price);

        $itemList = new ItemList();
        $itemList->setItems(array($item1));

// ### Additional payment details
// Use this optional field to set additional
// payment information such as tax, shipping
// charges etc.
        $details = new Details();
        $details->setShipping(0.0)
            ->setTax(0.0)
            ->setSubtotal($price * $quantity);

// ### Amount
// Lets you specify a payment amount.
// You can also specify additional details
// such as shipping, tax.
        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal($price * $quantity)
            ->setDetails($details);

// ### Transaction
// A transaction defines the contract of a
// payment - what is the payment for and who
// is fulfilling it.
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription($name)
            ->setInvoiceNumber(uniqid());


// ### Payment
// A Payment Resource; create one using
// the above types and intent set to 'sale'
        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($this->redirectUrls)
            ->setTransactions(array($transaction));

// ### Create Payment
// Create a payment by calling the 'create' method
// passing it a valid apiContext.
// (See bootstrap.php for more on `ApiContext`)
// The return object contains the state and the
// url to which the buyer must be redirected to
// for payment approval
        try {
            $payment->create($this->apiContext);
        } catch (Exception $ex) {
        }

// ### Get redirect url
// The API response provides the url that you must redirect
// the buyer to. Retrieve the url from the $payment->getApprovalLink()
// method
        return $payment;
    }

    public function processApproval(Request $request)
    {
        return $this->executePayment($request);
    }

    public function executePayment(Request $request)
    {

            $paymentId = $request->get('paymentId');
            $payment = Payment::get($paymentId, $this->apiContext);
        
            // ### Payment Execute
            // PaymentExecution object includes information necessary
            // to execute a PayPal account payment.
            // The payer_id is added to the request query parameters
            // when the user is redirected from paypal back to your site
            $execution = new PaymentExecution();
            $execution->setPayerId($request->get('PayerID'));

            // ### Optional Changes to Amount
            // If you wish to update the amount that you wish to charge the customer,
            // based on the shipping address or any other reason, you could
            // do that by passing the transaction object with just `amount` field in it.
            // Here is the example on how we changed the shipping to $1 more than before.
//            $transaction = new Transaction();
//            $amount = new Amount();
//            $details = new Details();
//
//            $details->setShipping(2.2)
//                ->setTax(1.3)
//                ->setSubtotal(17.50);
//
//            $amount->setCurrency('USD');
//            $amount->setTotal(21);
//            $amount->setDetails($details);
//            $transaction->setAmount($amount);

            // Add the above transaction object inside our Execution object.
          //  $execution->addTransaction($transaction);

            try {
                // Execute the payment
                // (See bootstrap.php for more on `ApiContext`)
                $result = $payment->execute($execution, $this->apiContext);

                try {
                    $payment = Payment::get($paymentId, $this->apiContext);
                } catch (Exception $ex) {

                }
            } catch (Exception $ex) {
                return false;
            }

            return $payment;
    }
}
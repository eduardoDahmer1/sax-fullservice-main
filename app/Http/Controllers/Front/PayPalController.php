<?php

namespace App\Http\Controllers\Front;

use App\Models\Order;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\PaymentExecution;
use Illuminate\Support\Facades\Log;
use PayPal\Api\Transaction;
use App\Traits\Gateway;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class PayPalController extends Controller
{
    use Gateway;

    public function __construct()
    {
        parent::__construct();

        $this->enabled = config("gateways.paypal");
        $this->checkCurrency = "BRL";
        $this->name = "PayPal";
        $this->credentials = [
            "client_id" => $this->storeSettings->paypal_client_id,
            "client_secret" => $this->storeSettings->paypal_secret
        ];

        $this->apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                $this->credentials["client_id"],
                $this->credentials["client_secret"]
            )
          );
        if ($this->storeSettings->is_paypal_sandbox == 1) {
            $this->apiContext->setConfig(
                array(
                  'log.LogEnabled' => true,
                  'log.FileName' => 'PayPal.log',
                  'log.LogLevel' => 'DEBUG',
                  'mode' => 'sandbox'
                )
          );
        } else {
            $this->apiContext->setConfig(
                array(
                  'log.LogEnabled' => true,
                  'log.FileName' => 'PayPal.log',
                  'log.LogLevel' => 'DEBUG',
                  'mode' => 'live'
                )
          );
        }

    }

    public function payment()
    {

        $notify_url = action('Front\PayPalController@paypalCallback');

        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $item = new Item();
        $item->setName($this->order->order_number)
        ->setCurrency($this->checkCurrency)
        ->setQuantity(1)
        ->setPrice(round($this->cartTotal["before_costs"], 2));

        $itemList = new ItemList();
        $itemList->setItems(array($item));

        $details = new Details();

        $details->setShipping($this->order->shipping_cost)
        ->setTax($this->order->tax)
        ->setSubtotal(round($this->cartTotal["before_costs"], 2));

        $amount = new Amount();
        $amount->setCurrency($this->checkCurrency)
        ->setTotal($this->order->pay_amount)
        ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
        ->setItemList($itemList)
        ->setDescription('Payment Description')
        ->setInvoiceNumber(uniqid());

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(route('paypal.notify'))
                     ->setCancelUrl($notify_url);

        $payment = new Payment();
        $payment->setIntent('sale')
        ->setPayer($payer)
        ->setRedirectUrls($redirectUrls)
        ->setTransactions([$transaction]); 
        
        $payment->create($this->apiContext);

        Session::put('paypal', ['order_number' => $this->order->order_number]);
        
        $this->paymentUrl = $payment->getApprovalLink();

    }

    public function paypalCallback(Request $request)
    {
        
        $order_id = Session::get('paypal')['order_number'];
        $order = Order::where('order_number', $order_id)->first();

        try{
            $paymentId = request('paymentId');
            $payment = Payment::get($paymentId, $this->apiContext);
            $execution = new PaymentExecution();
            $execution->setPayerId(request('PayerID'));
    
            $transaction = new Transaction();
            $amount = new Amount();
            $details = new Details();
    
            $details->setSubtotal($order->pay_amount - $order->shipping_cost);
    
            //retrieving invoice number to pass to order
            $invoice_number = $payment->getTransactions();
            $invoice_number = $invoice_number[0];
    
            $amount->setCurrency($this->checkCurrency);
            $amount->setTotal($order->pay_amount);
            $amount->setDetails($details);
            $transaction->setAmount($amount);
            
            $result = $payment->execute($execution, $this->apiContext);

            if (!empty($invoice_number->invoice_number) && $result->state == "approved") {
                if (isset($order)) {
                    $data['charge_id'] = $paymentId;
                    $data['txnid'] = $invoice_number->invoice_number;
                    $data['payment_status'] = 'Completed';
    
                    if ($order->dp == 1) {
                        $data['status'] = 'completed';
                    }
    
                    $order->update($data);
    
                    $notification = new Notification;
                    $notification->order_id = $order->id;
                    $notification->save();

                    if(Session::has('temporder')){
                        Session::forget('temporder');
                    }
    
                    Session::put('temporder', $order);
                    Session::put('paypal', ['order_number' => $order_id]);
    
                    if(Session::has("order")){
                        Session::forget('order');
                    }

                    return redirect(route('payment.return'));
                }
            } else {
                return redirect(route('front.checkout'));
            }
        }catch(Exception $e)
        {
            Log::debug('paypal_response', [$e]);
            return redirect(route('front.checkout'));
        }
        

    }


}

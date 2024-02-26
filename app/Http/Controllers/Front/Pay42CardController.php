<?php

namespace App\Http\Controllers\Front;

use Exception;
use App\Models\Order;
use App\Models\Country;
use App\Traits\Gateway;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class Pay42CardController extends Controller
{
    use Gateway;

    private const INVALID_API_KEY = 1;

    private $currency;

    private $appUrl;

    private $documentType;

    public function __construct()
    {
        parent::__construct();

        $this->enabled = config("gateways.pay42");
        $this->checkCurrency = $this->storeSettings->pay42_currency;
        $this->name = "Pay42 Card";
        $this->credentials = [
            "token" => $this->storeSettings->pay42_token,
        ];
        
        $this->apiUrl = "https://api.pay42.com.br/v2"; 
        if($this->storeSettings->is_pay42_sandbox){
            $this->apiUrl = "https://sandbox.pay42.com.br/v2"; 
        }

        $appUrl = url('/').'/api/pay42/card-notifications';

        $this->documentType = "CPF";
    }

    protected function payment()
    {
        Session::put('cartTotal', $this->cartTotal);
        Session::put('order', $this->order);
        $this->paymentUrl = route('pay42.callForm', $this->order->id);
    }

    public function callForm(Request $pedido)
    {
        $pedido = Order::find($pedido->route('pedido'));
        if(empty($pedido)) return view('errors.404');
        return view('front.pay42.form', ['pedido' => $pedido]);
    }

    public function pay42Callback(Request $request)
    {
        $order = Session::get('order');

        $cartTotal = Session::get('cartTotal');

        if(!isset($order['customer_name'])){
            return redirect()->route('front.checkout');
        }

        $customerName = $order['customer_name'];
        $documentNumber = $order['customer_document'];
        $customerEmail = $order['customer_email'];
        $transactionId = $order['id'];
        $amount = $order['pay_amount'] * $order['currency_value'];
        $phone = $order['customer_phone'];
        $customerZipCode = $order['customer_zip'];

        if(!isset($cartTotal)){
            return redirect()->route('front.checkout');
        }

        $cart_total_without_shipping = $cartTotal["before_costs"] + $order['packing_cost'];
        $cart_total_without_shipping_currency = $cart_total_without_shipping * $order['currency_value'];

        $installments = $request->get('installments');
        $cardHolderName = $request->get('cardHolderName');

        if($installments && $installments > 12  || $installments < 1){
            $error = __("installments: Invalid parameter format");
        }

        $cardNumber = preg_replace('/[^0-9]/', null, $request->get('cardNumber'));
        if (empty($cardNumber)) {
            if($request->ajax()) {
                return response()->json([
                    'unsuccess' => __("cardNumber: Invalid parameter format")
                ],404);
            }
            $error = __("cardNumber: Invalid parameter format");
        }

        $securityCode = preg_replace('/[^0-9]/', null, $request->get('securityCode'));
        if (empty($securityCode)) {
            if($request->ajax()) {
                return response()->json([
                    'unsuccess' => __("securityCode: Invalid parameter format")
                ],404);
            }
            $error = __("securityCode: Invalid parameter format");
        }

        if (strlen($securityCode) > 4) {
            $error = __("securityCode: Invalid parameter format");
        }

        $expirationMonth = preg_replace('/[^0-9]/', null, $request->get('expirationMonth'));
        if (empty($expirationMonth)) {
            if($request->ajax()) {
                return response()->json([
                    'unsuccess' => __("expirationMonth: Invalid parameter format")
                ],404);
            }
            $error = __("expirationMonth: Invalid parameter format");
        }

        if (strlen($expirationMonth) > 2) {
            $error = __("expirationMonth: Invalid parameter format");
        }

        $expirationYear = preg_replace('/[^0-9]/', null, $request->get('expirationYear'));
        if (empty($expirationYear)) {
            if($request->ajax()) {
                return response()->json([
                    'unsuccess' => __("expirationYear: Invalid parameter format")
                ],404);
            }
            $error = __("expirationYear: Invalid parameter format");
        }

        $expirationYear = $expirationYear + 2000;

        if (strlen($expirationYear) > 4) {
            $error = __("expirationYear: Invalid parameter format");
        }

        if ($expirationYear < now()->year) {
            $error = __("expirationYear: Expired");
        }

        if ($expirationYear == now()->year && $expirationMonth < now()->month) {
            $error = __("expirationMonth: Expired");
        }

        if(!empty($error)){
            $updateDetails = [
                'status' => 'declined'
            ];
    
            $order = Order::where('id',$transactionId)
            ->update($updateDetails);

            return redirect()->route('front.checkout')->withInput()->with('unsuccess',$error);
        }


        if(Session::has('cartTotal')){
            Session::forget('cartTotal');
        }

        //***End of card validations

        $cnpj = strlen($documentNumber);
        if($cnpj === 14 ){
            $this->documentType = "CNPJ";
        }

        $phone = preg_replace('/[^0-9]/', null, $phone);
        
        $zipcode = $customerZipCode;
        $zipcode = preg_replace('/[^0-9]/', null, $zipcode);

        $orderData = array(
            'transaction_id' => $transactionId,
            'name' => $customerName,
            'amount' => $amount,
            'currency' => $this->checkCurrency,
            'installments' => $installments,
            'document_type' => $this->documentType,
            'document_number' => $documentNumber,
            'email'=> $customerEmail,
            'card' => [ 
                'card_number' => $cardNumber,
                'cardholder_name' => $cardHolderName,
                'security_code' => $securityCode,
                'expiration_month' => $expirationMonth,
                'expiration_year' => strval($expirationYear),
            ],
        );

        $data_post = json_encode($orderData);
        $mediaType = "application/json";
        $charSet = "UTF-8";
        $headers = [];
        $headers[] = "Content-Type: ".$mediaType;
        $headers[] = "Authorization: ". $this->credentials['token'];

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $this->apiUrl."/card",
            CURLOPT_POSTFIELDS => $data_post,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
        ));
        $result = curl_exec($ch);
        $json = json_decode($result, true);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        # If any exception...
        if($httpCode !== 200) {
            Log::debug('pay42_pix_response', [$json]);

            $order = Order::where('id',$transactionId)
            ->update(['status' => 'declined']);

            if(isset($json['message'])) return redirect()->route('front.checkout')->withInput()->with('unsuccess', $json['message']);
            return redirect()->route('front.checkout');
        }

        $id = $json['id'];
        $transaction_id = $json['transaction_id'];
        $status = $json['status'];
        $currency = $json['currency'];
        $pay_amount = $json['amount'];
        $card_status = $json['card_status'];
        $card_status_details = $json['card_status_details'];

        if($status == 2){
            $error = "";
            Log::debug("Pay42 card Debug: ", [$card_status_details]);
            
            if($card_status == "Not Authorized"){
                $error = __("Card: Not authorized");
            }
            if($card_status == "Card Expired"){
                $error = __("Card: Expired card");
            }
            if($card_status == "Blocked Card"){
                $error = __("Card: Blocked card");
            }
            if($card_status == "Timeout"){
                $error = __("Card: Time Out");
            }
            if($card_status == "Card Canceled"){
                $error = __("Card: Canceled card");
            }

            $order = Order::where('id',$transaction_id)
            ->update(['status' => 'declined']);

            return redirect()->route('front.checkout')->withInput()->with('unsuccess', $error);
        }

        if(isset($currency)) {
            if($currency == "USD"){
                $total = $json['amount'];
                $updateDetails = [
                    'pay42_total' => $total,
                ];

                $order = Order::where('id',$json['transaction_id'])
                ->update($updateDetails);
            }
        }
        
        $order = Order::where('id',$json['transaction_id'])
        ->update(['txnid' => $json['id']]);
        curl_close($ch);
        
        $oldCart = Session::get('temporder');
        $oldCart->txnid = $id;

        if(isset($total) && isset($exchange)){
            $oldCart->pay42_total = $total;
            $oldCart->pay42_exchange_rate = $exchange;
        }
        
        $updateDetails = [
            'payment_status' => 'Completed',
            'status' => 'processing'
        ];

        $order = Order::where('id',$transaction_id)
        ->update($updateDetails);

        Session::put('temporder', $oldCart);


        if (Session::has("order")) {
            Session::forget('order');
        }

        return redirect(route('payment.return'));



    }
        
}


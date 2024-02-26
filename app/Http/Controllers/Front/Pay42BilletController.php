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

class Pay42BilletController extends Controller
{
    use Gateway;

    private const INVALID_API_KEY = 1;

    private $currency;

    private $appUrl;

    private $dueDate;

    private $documentType;

    public function __construct()
    {
        parent::__construct();

        $this->enabled = config("gateways.pay42");
        $this->checkCurrency = $this->storeSettings->pay42_currency;
        $this->name = "Pay42 Billet";
        $this->credentials = [
            "token" => $this->storeSettings->pay42_token,
        ];
        
        $this->apiUrl = "https://api.pay42.com.br/v2";
        if($this->storeSettings->is_pay42_sandbox){
            $this->apiUrl = "https://sandbox.pay42.com.br/v2";
        }

        $this->appUrl = url('/').'/api/pay42/billet-notifications';

        $this->dueDate = now();
        $this->dueDate->addDays($this->storeSettings->pay42_due_date);
        if(!$this->storeSettings->pay42_due_date){
           $this->dueDate->addDays(1);
        }
        $this->dueDate = $this->dueDate->format('Y-m-d');
        $this->documentType = "CPF";
    }

    protected function payment()
    {
        $cnpj = strlen($this->order['customer_document']);
        if($cnpj === 14 ){
            $this->documentType = "CNPJ";
        }

        $phone = preg_replace('/[^0-9]/', null, $this->order->customer_phone);
        if(strlen($phone) > 11) {
            $phone = substr($phone, 2);
        }

        if(strlen($phone) < 10) {
            $this->paymentErrors['phone'] = __('Invalid Phone number. It should have 10 or 11 digits');
        }
        $zipcode = $this->order->customer_zip;
        $zipcode = preg_replace('/[^0-9]/', null, $zipcode);

        $cart_total_without_shipping = $this->cartTotal["before_costs"] + $this->order['packing_cost'];
        $cart_total_without_shipping_currency = $cart_total_without_shipping * $this->order['currency_value'];

        $orderData = array(
            'transaction_id' => $this->order['id'],
            'due' => $this->dueDate,
            'name' => $this->order['customer_name'],
            'document_number' => $this->order['customer_document'],
            'document_type' => $this->documentType,
            'currency' => $this->checkCurrency,
            'amount' => $this->order['pay_amount']* $this->order['currency_value'],
            'address' => [
                'street' => $this->order['customer_address'],
                'number' => $this->order['customer_address_number'],
                'complement' =>$this->order['customer_complement'] ?? "",
                'district' =>$this->order['customer_district'],
                'city' => $this->order['customer_city'],
                'state' =>$this->stateCode,
                'zipcode' => $zipcode,
            ],
            'webhook' => $this->appUrl,
        );

        $data_post = json_encode($orderData);
        $mediaType = "application/json";
        $charSet = "UTF-8";
        $headers = [];
        $headers[] = "Content-Type: ".$mediaType;
        $headers[] = "Authorization: ". $this->credentials['token'];

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $this->apiUrl."/boleto",
            CURLOPT_POSTFIELDS => $data_post,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
        ));

        $result = curl_exec($ch);
        $json = json_decode($result, true);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if(isset($json['code'])){
            if ($json['code'] === self::INVALID_API_KEY){
                $this->paymentJson['errors'] = $json;
                Log::debug('pay42_pix_response', [$json]);
                return redirect()->route('front.checkout');
            }
        }
        
        if(isset($json['type'])){
            if ($json['type'] === 'ValidationError'){
                $this->paymentJson['errors'] = $json;
                Log::debug('pay42_pix_response', [$json]);
                return redirect()->route('front.checkout');
            }
        }

        # If any exception...
        if($httpCode !== 200) {
            Log::debug('pay42_pix_response', [$json]);
            return redirect(route('front.checkout'));
        }

        if($json['currency'] == "USD"){
            $total = $json['total'];
            $exchange = $json['exchange_rate'];

            $updateDetails = [
                'pay42_total' => $total,
                'pay42_exchange_rate' => $exchange
            ];

            $order = Order::where('id',$json['transaction_id'])
            ->update($updateDetails);
        }

        $order = Order::where('id',$json['transaction_id'])
        ->update(['txnid' => $json['id']]);

        curl_close($ch);

        $id = $json['id'];
        $transaction_id = $json['transaction_id'];
        $status = $json['status'];
        $currency = $json['currency'];
        $amount = $json['amount'];
        $due = $json['due'];
        $line_code = $json['line_code'];
        $bar_code = $json['bar_code'];
        $url = $json['url'];

        $updateDetails = [
            'pay42_billet' => $url,
            'pay42_due_date' => $due
        ];

        Order::where('id',$transaction_id)->update($updateDetails);


        Session::put('pay42_pix_days_due_date', $due);

        $oldCart = Session::get('temporder');
        $oldCart->txnid = $id;
        $oldCart->pay42_billet = $url;

        if(isset($total) && isset($exchange)){
            $oldCart->pay42_total = $total;
            $oldCart->pay42_exchange_rate = $exchange;
        }

        Session::put('temporder', $oldCart);

        $this->paymentUrl = action('Front\PaymentController@payreturn');

        if (Session::has("order")) {
            Session::forget('order');
        }

        return;

    }

    public function notify(Request $request){
        Log::notice("Pay42 billet Notification: ", [$request->all()]);

        $webhook = $request->all();

        //paid
        if ($webhook['status'] == 1){
            $updateDetails = [
                'payment_status' => 'Completed',
                'status' => 'processing'
            ];
            $order = Order::where('id',$webhook['transaction_id'])
            ->update($updateDetails);
        }

        //expired
        if ($webhook['status'] == 3){
            $order = Order::where('id',$webhook['transaction_id'])
            ->update(['status' => 'declined']);

        }

    }
}

<?php

namespace App\Http\Controllers\Front;

use App\Models\Order;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Traits\Gateway;
use Illuminate\Support\Facades\Session;

class PaghiperController extends Controller
{
    use Gateway;

    public function __construct()
    {
        parent::__construct();

        $this->enabled = config("gateways.paghiper");
        $this->checkCurrency = "BRL";
        $this->name = "Paghiper";
        $this->credentials = [
            "paghiper_token" => $this->storeSettings->paghiper_token,
            "paghiper_api_key" => $this->storeSettings->paghiper_api_key,
            "paghiper_days_due_date" => $this->storeSettings->paghiper_days_due_date
        ];

        $this->apiUrl = "https://api.paghiper.com/transaction/create/";
    }

    protected function payment()
    {

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

        if($this->storeSettings->paghiper_is_discount){
            $total = $cart_total_without_shipping_currency = $cart_total_without_shipping * $this->order['currency_value'];
            $discount = ($total / 100) * $this->storeSettings->paghiper_discount;
            $discount_cents = $discount * 100;
        } else $discount_cents = 0;

        $orderData = array(
            'apiKey' => $this->credentials["paghiper_api_key"],
            'order_id' => $this->order->id, // código interno do lojista para identificar a transacao.
            'payer_email' => $this->order->customer_email,
            'payer_name' => $this->order->customer_name, // nome completo ou razao social
            'payer_cpf_cnpj' => $this->order->customer_document, // cpf ou cnpj
            'payer_phone' => $phone, // fixou ou móvel
            'payer_street' => $this->order->customer_address,
            'payer_number' => $this->order->customer_address_number,
            'payer_complement' => $this->order->customer_complement,
            'payer_district' => $this->order->customer_district,
            'payer_city' => $this->order->customer_city,
            'payer_state' => session()->get('session_order')['customer_state_initials'], // apenas sigla do estado
            'payer_zip_code' => $zipcode,
            'notification_url' => action('Front\PaymentController@payreturn'),
            'discount_cents' => (int)$discount_cents, // em centavos
            'shipping_price_cents' => $this->order->shipping_cost * 100, // em centavos
            'shipping_methods' => $this->order->shipping_type,
            'fixed_description' => true,
            'type_bank_slip' => 'boletoA4', // formato do boleto
            'days_due_date' => $this->credentials["paghiper_days_due_date"], // dias para vencimento do boleto
            'late_payment_fine' => '1',// Percentual de multa após vencimento.
            'per_day_interest' => true, // Juros após vencimento.
            'items' => array(
                array ('description' => 'Pedido #'.$this->order->order_number,
                    'quantity' => '1',
                    'item_id' => $this->order->id,
                    'price_cents' => $cart_total_without_shipping_currency * 100
                    ),
            ),
        );

        $data_post = json_encode($orderData);

        $mediaType = "application/json"; // formato da requisição
        $charSet = "UTF-8";
        $headers = array();
        $headers[] = "Accept: ".$mediaType;
        $headers[] = "Accept-Charset: ".$charSet;
        $headers[] = "Accept-Encoding: ".$mediaType;
        $headers[] = "Content-Type: ".$mediaType.";charset=".$charSet;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if($result = curl_exec($ch)){
            $json = json_decode($result, true);

            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if($httpCode == 201){

                $message = __('Please access your billet clicking on the button. It will open in a new tab.');
                $url = $json['create_request']['bank_slip']['url_slip_pdf'];
                $urlTitle = __('Access billet');

                Session::put('gateway_message', $message);
                Session::put('gateway_url', $url);
                Session::put('gateway_url_title', $urlTitle);
                $order_id = $json['create_request']['order_id'];
                $order = Order::where('id', $order_id)->first();
                if(isset($order)){
                    $txnid = $json['create_request']['transaction_id'];
                    $pay_amount = (double)$json['create_request']['value_cents'] / 100;
                    if($order->update(['txnid' => $txnid, 'pay_amount' => $pay_amount])){
                        $this->paymentUrl = action('Front\PaymentController@payreturn');
                        if(Session::has("order")){
                            Session::forget('order');
                        }
                        return;
                    }
                }
            } else{
                Log::debug('paghiper_response', [$json]);
                return redirect(route('front.checkout'));
            }
        } else{
            Log::debug('paghiper_curl_response', [$ch]);
            return redirect(route('front.checkout'));
        }
    }
}

<?php

namespace App\Http\Controllers\Front;

use App\Models\Order;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Gateway;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class RedeController extends Controller
{
    use Gateway;

    public function __construct()
    {
        parent::__construct();

        $this->enabled = config("gateways.rede");
        $this->checkCurrency = "BRL";
        $this->name = "Rede";
        $this->credentials = [
            "TOKEN" => $this->storeSettings->rede_token,
            "PV" => $this->storeSettings->rede_pv,
        ];

        if($this->storeSettings->is_rede_sandbox == 1){
            $this->environment = \Rede\Environment::sandbox();
        }else{
            $this->environment = \Rede\Environment::production();
        }
    }

    protected function payment()
    {
        $this->paymentUrl = route('rede.callForm', $this->order->id);
    }

    public function callForm(Request $pedido)
    {
        $pedido = Order::find($pedido->route('pedido'));
        return view('front.rede.form', ['pedido' => $pedido]);
    }

    public function redeCallback(Request $request)
    {
        // Configuração da loja em modo sandbox ou produção
        $store = new \Rede\Store($this->credentials["PV"], $this->credentials["TOKEN"], $this->environment);

        //--------------------------------------------- begin sanitization ---------------------------------------------
        $cardNumber = preg_replace('/[^0-9]/', null, $request->get('cardNumber'));
        if (empty($cardNumber)) {
            if($request->ajax()) {
                return response()->json([
                    'unsuccess' => __("cardNumber: Invalid parameter format")
                ],404);
            }
            return redirect()->back()->withInput()->with('unsuccess', __("cardNumber: Invalid parameter format"));
        }

        $securityCode = preg_replace('/[^0-9]/', null, $request->get('securityCode'));
        if (empty($securityCode)) {
            if($request->ajax()) {
                return response()->json([
                    'unsuccess' => __("securityCode: Invalid parameter format")
                ],404);
            }
            return redirect()->back()->withInput()->with('unsuccess', __("securityCode: Invalid parameter format"));
        }

        if (strlen($securityCode) > 4) {
            return redirect()->back()->withInput()->with('unsuccess', __("securityCode: Invalid parameter format"));
        }

        $expirationMonth = preg_replace('/[^0-9]/', null, $request->get('expirationMonth'));
        if (empty($expirationMonth)) {
            if($request->ajax()) {
                return response()->json([
                    'unsuccess' => __("expirationMonth: Invalid parameter format")
                ],404);
            }
            return redirect()->back()->withInput()->with('unsuccess', __("expirationMonth: Invalid parameter format"));
        }

        if (strlen($expirationMonth) > 4) {
            return redirect()->back()->withInput()->with('unsuccess', __("expirationMonth: Invalid parameter format"));
        }

        $expirationYear = preg_replace('/[^0-9]/', null, $request->get('expirationYear'));
        if (empty($expirationYear)) {
            if($request->ajax()) {
                return response()->json([
                    'unsuccess' => __("expirationYear: Invalid parameter format")
                ],404);
            }
            return redirect()->back()->withInput()->with('unsuccess', __("expirationYear: Invalid parameter format"));
        }

        if (strlen($expirationYear) > 4) {
            return redirect()->back()->withInput()->with('unsuccess', __("expirationYear: Invalid parameter format"));
        }
        //--------------------------------------------- end sanitization ---------------------------------------------

        $method = $request->get('kind');

        // Transação que será autorizada
        $transaction = (new \Rede\Transaction($request->get('Amount'), $request->get('reference'), 'pedido' . time()))->$method(
            $cardNumber,
            $securityCode,
            $expirationMonth,
            $expirationYear,
            $request->get('cardHolderName')
        );

        if ($method != 'debitCard') {
            // Configuração de parcelamento
            $transaction->setInstallments($request->get('installments'));
        }

        if ($request->get('cardHolderName') == null || $request->get('cardHolderName') == "") {
            return redirect(route('rede.callForm', Session::get('temporder.id')))->withInput()->with('unsuccess',  __("cardHolderName: Required parameter missing"));
        }

        try {
            // Autoriza a transação
            $transaction = (new \Rede\eRede($store))->create($transaction);

            if ($transaction->getReturnCode() == '00') {

                $order_id = $request->get('reference');
                $order = Order::where('order_number', $order_id)->first();

                if (isset($order)) {
                    $data['charge_id'] = $transaction->getTid();
                    $data['txnid'] = $transaction->getTid();
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
                    Session::put('rede', ['order_number' => $order_id]);
                    if(Session::has("order")){
                        Session::forget('order');
                    }
                    return redirect(route('payment.return'));
                }
            }

        } catch (\Rede\Exception\RedeException $e) {
            Log::error('rede_callback', [$e]);
            return redirect(route('rede.callForm', Session::get('temporder.id')))->withInput()->with('unsuccess', $e->getMessage());
        }
    }
}

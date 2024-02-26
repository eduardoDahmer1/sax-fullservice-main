<?php

namespace App\Http\Controllers\Front;

use App\Models\Order;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Gateway;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class PagseguroController extends Controller
{
    use Gateway;

    const PAYMENT_STATUS_WAITING = "1";
    const PAYMENT_STATUS_NOT_FINISH = "2";
    const PAYMENT_STATUS_PAID = "3";
    const PAYMENT_STATUS_AVAIABLE = "4";
    const PAYMENT_STATUS_DISPUTE = "5";
    const PAYMENT_STATUS_CHARGEBACK = "6";
    const PAYMENT_STATUS_CANCELED = "7";

    public function __construct()
    {
        return; // not compatible yet

        parent::__construct();

        $this->enabled = config("gateways.pagseguro");
        $this->checkCurrency = "BRL";
        $this->name = "PagSeguro";
        $this->credentials = [
            "token" => $this->storeSettings->pagseguro_token,
            "email" => $this->storeSettings->pagseguro_email
        ];
        if ($this->storeSettings->is_pagseguro_sandbox == 1) {
            $this->environment = "sandbox";
        } else {
            $this->environment = "production";
        }

        \PagSeguro\Library::initialize();
        \PagSeguro\Library::cmsVersion()->setName("Nome")->setRelease("1.0.0");
        \PagSeguro\Library::moduleVersion()->setName("Nome")->setRelease("1.0.0");

        \PagSeguro\Configuration\Configure::setEnvironment($this->environment);//production or sandbox
        \PagSeguro\Configuration\Configure::setAccountCredentials(
            $this->credentials["email"],
            $this->credentials["token"]
        );

        \PagSeguro\Configuration\Configure::setCharset('UTF-8');// UTF-8 or ISO-8859-1
        \PagSeguro\Configuration\Configure::setLog(true, public_path().'/project/storage/logs/pagseguro.log');
    }

    protected function payment()
    {
        $notify_url = action('Front\PagseguroController@pagseguroCallback');

        $payment = new \PagSeguro\Domains\Requests\Payment();
        $payment->addItems()->withParameters(
            $this->order->order_number,
            $this->storeSettings->title,
            1,
            $this->cartTotalCurrency["after_costs"]
        );

        $payment->setCurrency($this->checkCurrency);
        $payment->setReference($this->order->order_number);
        $payment->setRedirectUrl(action('Front\PaymentController@payreturn'));

        // Set your customer information.

        # Créditos do algoritmo abaixo
        # https://sounoob.com.br/resolvendo-o-erro-sendername-invalid-do-pagseguro-usando-php/

        # Remove números
        $name = preg_replace('/\d/', '', $this->order->customer_name);
        # Remove tabulações e quebras de linha
        $name = preg_replace('/[\n\t\r]/', ' ', $name);
        # Remove espaços duplicados
        $name = preg_replace('/\s(?=\s)/', '', $name);
        # Remove espaços do começo e fim do nome
        $name = trim($name);
        # Transforma o nome em Array pra verificar nome + sobrenome
        $name = explode(' ', $name);
        # Atribui um "sobrenome" para o cliente caso não tenha
        if (count($name) == 1) {
            $name[] = ' Cliente';
        }
        # Retorna o nome a uma string novamente
        $name = implode(' ', $name);

        $payment->setSender()->setName($name);
        $payment->setSender()->setEmail($this->order->customer_email);
        $payment->setShipping()->setAddressRequired()->withParameters('FALSE');
        //$payment->setSender()->setPhone()->withParameters("",$this->order->customer_phone);
        //$payment->setSender()->setDocument()->withParameters('CPF',$this->order->customer_document);
        $payment->setNotificationUrl($notify_url);

        try {
            $result = $payment->register(
                \PagSeguro\Configuration\Configure::getAccountCredentials()
            );
        } catch (\Exception $e) {
            $this->paymentJson['errors'] = [
                '<br><br><pre>' . $e->getMessage() . "</pre>"
            ];
            return;
        }
        if (!empty($result)) {
            $this->paymentUrl = $result;
            return;
        }
    }

    public function pagseguroCallback()
    {
        if (\PagSeguro\Helpers\Xhr::hasPost()) {
            $response = \PagSeguro\Services\Transactions\Notification::check(
                \PagSeguro\Configuration\Configure::getAccountCredentials()
            );
            if (!empty($response)) {
                $order = Order::where('order_number', $response->getReference())->first();
                if (!empty($order)) {
                    $info["charge_id"] = $response->getCode();
                    $info["txnid"] = $response->getReference();
                    if (
                        $response->getStatus() == self::PAYMENT_STATUS_PAID ||
                        $response->getStatus() == self::PAYMENT_STATUS_AVAIABLE
                    ) {
                        $info["payment_status"] = "Completed";
                        if ($order->dp == 1) {
                            $info["status"] = "completed";
                        }
                    }
                    $order->update($info);

                    $notification = new Notification;
                    $notification->order_id = $order->id;
                    $notification->save();
                    if (Session::has("order")) {
                        Session::forget('order');
                    }

                    echo "Status atualizado";
                } else {
                    throw new \InvalidArgumentException($_POST);
                }
            } else {
                throw new \InvalidArgumentException($_POST);
            }
        } else {
            throw new \InvalidArgumentException($_POST);
        }
    }
}

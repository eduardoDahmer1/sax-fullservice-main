<?php

namespace App\Listeners;

use App\Events\WatchPix;
use App\Models\Currency;
use App\Models\Generalsetting;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class HandleWatchPix implements ShouldQueue
{
    use InteractsWithQueue;

    private $order;
    private $storeSettings;

    public $timeout = 600;
    public $tries = 100;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->storeSettings = GeneralSetting::where('is_default',1)->firstOrFail();
    }

    /**
     * Handle the event.
     *
     * @param  WatchPix  $event
     * @return void
     */
    public function handle(WatchPix $event)
    {
        $this->order = $event->getOrder();

        # Tries for the first time...
        $tries = 0;

        /*
        * Possible Status: 
        * pending
        * canceled
        * completed
        * paid
        * processing
        * refunded
        */
        while($tries < 5) {
            Log::debug("Trying to get PIX...");
            $pix = $this->getPix();
            $pix_status = $pix->status_request->status;

            # Set Order as paid if PIX is paid...
            if($pix_status == "paid") {
                $this->order->payment_status = "Completed";
                $this->order->update();
                return;
            }

            # Cancel order if PIX is canceled...
            if($pix_status == "canceled") {
                $this->order->status = "declined";
                $this->order->payment_status = "Pending";
                $this->order->update();
                return;
            }
            $tries++;
            sleep(60);
        }

        Log::debug('5 tentativas de resgatar o PIX, cancelando pedido...');
        $this->order->status = "declined";
        $this->order->payment_status = "Pending";
        $this->order->update();
        return;
    }

    private function getPix()
    {
        $transaction_id = $this->order->txnid;
        $token = $this->storeSettings->paghiper_token;
        $api_key = $this->storeSettings->paghiper_api_key;

        $data = array(
            "token" => $token,
            "apiKey" => $api_key,
            "transaction_id" => $transaction_id
        );

        $data_post = json_encode($data);

        $mediaType = "application/json";
        $charSet = "UTF-8";
        
        $headers = array();
        $headers[] = "Accept: ".$mediaType;
        $headers[] = "Accept-Charset: ".$charSet;
        $headers[] = "Accept-Encoding: ".$mediaType;
        $headers[] = "Content-Type: ".$mediaType.";charset=".$charSet;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://pix.paghiper.com/invoice/status/");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        $json = json_decode($result);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        # If any exception...
        if($httpCode != 201) {
            Log::debug('paghiper_pix_status_response', [$json]);
        }

        curl_close($ch);

        return $json;
    }
}

<?php

namespace App\Jobs;

use App\Models\Order;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderBilling implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url, $order;

    public $failOnTimeout = true;
    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($url, Order $order)
    {
        $this->url = $url;
        $this->order = $order;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $response = Http::withoutVerifying()->post($this->url);

            if ($response->failed()) {
                Log::debug('Erro na API Consoft');
            }
    
            if ($response->successful()) {
                foreach ($response->collect() as $data) {
                    if ($data['estatus'] == 0) {
                        $this->order->billing = 1;
                        $this->order->update();
                    }
                }
            }
        } catch (Exception $httpError) {
            Log::debug('Erro ao tentar enviar para API Consoft: ' . $httpError->getMessage());
        }
    }
}

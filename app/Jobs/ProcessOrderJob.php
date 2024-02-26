<?php

namespace App\Jobs;

use App\Models\Order;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ProcessOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $url, $order;

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
                    if (isset($data['ped']) && $data['ped']) {
                        $this->order->number_cec = $data['ped'];
                        $this->order->update();
                    }
                }
            }
        } catch (Exception $httpError) {
            Log::debug('Erro ao tentar enviar para API Consoft: ' . $httpError->getMessage());
        }
    }
}

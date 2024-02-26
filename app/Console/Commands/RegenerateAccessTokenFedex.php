<?php

namespace App\Console\Commands;

use App\Models\FedexConf;
use App\Models\Generalsetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RegenerateAccessTokenFedex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'regenerate:token-fedex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerates Access Token for Fedex.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $gs = Generalsetting::findOrFail(1);
        $data = Session::has('admstore') ? Session::get('admstore') : $gs;

        if ($data->fedex->production) {
            $url = 'https://apis.fedex.com';
        }

        if (!$data->fedex->production) {
            $url = 'https://apis-sandbox.fedex.com';
        }

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];

        $body = [
            'client_id'     => $data->fedex->client_id,
            'client_secret' => $data->fedex->client_secret,
            'grant_type'    => 'client_credentials'
        ];

        try {
            $response = Http::asForm()
                ->withHeaders($headers)
                ->post($url . '/oauth/token', $body);

            $response = json_decode($response->getBody()->getContents(), true);
            
            if (isset($response['errors'])) {
                Log::error("FEDEX_REGENERATE_ACCESS_TOKEN_ERROR: ", $response['errors']);
                return $response['errors'];
            }

            $data->fedex->update([
                'access_token' => $response['access_token']
            ]);

            $this->info('The FedEx token was successfully generated!');

            return $response;

        } catch (HttpException $e) {
            Log::error("FEDEX_REGENERATE_ACCESS_TOKEN_ERROR: ",  $e->getMessage());
        }
    }
}

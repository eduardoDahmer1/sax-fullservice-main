<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Helpers\Helper;
use App\Models\MercadoLivre;
use Illuminate\Support\Facades\Log;

class GenerateAccessToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates Access Token for Mercado Livre.';

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

        /* This code will run for the first time after Mercado Livre is configured on customer system.
        *  Maybe this will be thrown via button.
        */

        $url = config('mercadolivre.api_base_url');
        $curl = curl_init();

        $meli = MercadoLivre::first();

        /* Data to be sent in request. */
        $data = [
            "grant_type" => 'authorization_code',
            "client_id" => $meli->app_id,
            "client_secret" => $meli->client_secret,
            "code" => $meli->authorization_code,
            "redirect_uri" => $meli->redirect_uri,
        ];

        /* Configuration to send a POST to api_base_uri/oauth/token according to documentation */
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . "oauth/token",
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Content-Type: application/x-www-form-urlencoded",
            ),
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode($data),
        ));
        $resp = curl_exec($curl);
        $resp = (array) json_decode($resp);

        /* If no STATUS property has been thrown, means that request was successful.
        *  In this case, it will just save the given credentials in Database for future use.
        */
        if(!array_key_exists('status', $resp)) {
            $meli = MercadoLivre::first();
            $meli->access_token = $resp['access_token'];
            $meli->refresh_token = $resp['refresh_token'];
            $meli->update();

            Log::info("MELI_GENERATE_ACCESS_TOKEN_SUCCESS: ", [$resp]);
            return true;
        }

        /*
        * Just throw an error. Maybe it will do something with Meli database record as well, but its not yet implemented.
        */
        if(array_key_exists('status', $resp)) {
            Log::error('MELI_GENERATE_ACCESS_TOKEN_ERROR: ', [$resp]);
            return false;
        }
    }
}

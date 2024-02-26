<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FedexConf;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FedexController extends Controller
{
    public function authorization()
    {
        $fedex = FedexConf::first();

        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;

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
            'client_id'     => $fedex->client_id,
            'client_secret' => $fedex->client_secret,
            'grant_type'    => 'client_credentials'
        ];

        try {
            $response = Http::asForm()
                ->withHeaders($headers)
                ->post($url . '/oauth/token', $body);

            $response = json_decode($response->getBody()->getContents(), true);

            if (isset($response['errors'])) {
                return $response['errors'];
            }

            if (!is_null($fedex)) {
                $fedex->update([
                    'access_token' => $response['access_token']
                ]);
            };

            return $response;
        } catch (HttpException $e) {
            return $e->getMessage();
        }
    }
}
<?php

namespace App\Services;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;

class Bling
{
    private string $url = "https://www.bling.com.br/Api/v3/oauth/";
    public $access_token;
    public $refresh_token;

    private string $client_id;
    private string $client_secret;
    private string $state;

    public function __construct(string $access_token = null, string $refresh_token = null)
    {
        $this->client_id = config('services.bling.id');
        $this->client_secret = config('services.bling.secret');
        $this->state = config('services.bling.state');
        $this->access_token = $access_token;
        $this->refresh_token = $refresh_token;
    }

    public function authorize(): RedirectResponse
    {
        $params = [
            'response_type' => 'code',
            'client_id' => $this->client_id,
            'state' => $this->state,
        ];

        return redirect()->away($this->url . 'authorize?' . http_build_query($params));
    }

    public function generateTokens(string $code): void
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->client_id . ':' . $this->client_secret)
        ])->post($this->url . 'token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
        ])->collect();

        $this->access_token = $response->get('access_token');
        $this->refresh_token = $response->get('refresh_token');
    }

    public function isValidState(string $state)
    {
        return $this->state === $state;
    }

    public function refreshAccessToken(): void
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->client_id . ':' . $this->client_secret)
        ])->post($this->url . 'token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->refresh_token,
        ])->collect();

        $this->access_token = $response->get('access_token');
    }
}

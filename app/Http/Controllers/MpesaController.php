<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class MpesaController extends Controller
{
    protected $consumerKey;
    protected $consumerSecret;
    protected $client;

    public function __construct()
    {
        $this->consumerKey = env('MPESA_CONSUMER_KEY');
        $this->consumerSecret = env('MPESA_CONSUMER_SECRET');
        $this->client = new Client();
    }

    public function getAccessToken()
    {
        $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $credentials = base64_encode($this->consumerKey . ':' . $this->consumerSecret);

        $response = $this->client->request('GET', $url, [
            'headers' => [
                'Authorization' => 'Basic ' . $credentials,
            ],
        ]);

        $body = json_decode($response->getBody());

        return response()->json([
            'access_token' => $body->access_token
        ]);
    }
}

<?php

namespace App\Services;
use GuzzleHttp\Client;
use AfricasTalking\SDK\AfricasTalking;

class SmsService
{
    protected $africasTalking;
    protected $baseUri;

    public function __construct()
    {
        $username = config('africastalking.username');
        $apiKey = config('africastalking.api_key');
        $env = config('africastalking.env', 'sandbox');

        $this->baseUri = $env === 'live' 
            ? 'https://api.africastalking.com' 
            : 'https://api.sandbox.africastalking.com';

        $this->africasTalking = new AfricasTalking($username, $apiKey);
    }

    public function sendSms($recipients, $message, $from = null)
    {
        $client = new Client([
            'base_uri' => $this->baseUri,
            'timeout'  => 10.0,
            'curl'     => [
                CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
            ],
        ]);

        try {
            $response = $client->post('/version1/messaging', [
                'headers' => [
                    'apiKey' => config('africastalking.api_key'),
                ],
                'form_params' => [
                    'username' => config('africastalking.username'),
                    'to'       => $recipients,
                    'message'  => $message,
                    'from'     => $from,
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
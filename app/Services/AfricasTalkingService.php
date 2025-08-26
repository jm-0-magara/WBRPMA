<?php
namespace App\Services;

use AfricasTalking\SDK\AfricasTalking;
use Exception;

class AfricasTalkingService
{
    protected $sms;

    public function __construct()
    {
        $username = config('services.africastalking.username');
        $apiKey   = config('services.africastalking.api_key');

        $AT = new AfricasTalking($username, $apiKey);
        $this->sms = $AT->sms();
    }

    /**
     * Send SMS to an array of recipients.
     * @param array $to  Array of phone numbers in international format e.g. ['+2547XXXXXXXX']
     * @param string $message
     * @param string|null $from  optional sender id / short code
     * @return array  provider response
     * @throws Exception
     */
    public function send(array $to, string $message, ?string $from = null)
    {
        $payload = [
            'to'      => $to,
            'message' => $message,
        ];

       // Prefer a registered sender configured in services.php
        $configured = config('services.africastalking.sender');
        if ($configured && !$from) {
            $payload['from'] = $configured;
        } elseif ($from && !$configured) {
            // If you pass a from but you haven't configured sender, let it be — but
            // recommend not using personal numbers
            $payload['from'] = $from;
        }
        $response = $this->sms->send($payload);

        \Log::info('AT SMS raw response', ['payload' => $payload, 'response' => $response]);

        return $response;
    }
}

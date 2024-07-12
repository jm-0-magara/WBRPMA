<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SmsService;

class SmsController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function sendBroadcastSms(Request $request)
    {
        $recipients = $request->input('recipients');
        $message = $request->input('message');
        $from = '0708681664'; // Example sender, you can modify as needed

        // Log the data for debugging
        \Log::info('Sending SMS', [
            'recipients' => $recipients,
            'message' => $message,
            'from' => $from,
        ]);

        $response = $this->smsService->sendSms($recipients, $message, $from);

        return response()->json($response);
    }
}

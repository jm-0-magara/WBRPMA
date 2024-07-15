<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SmsService;
use Twilio\Rest\Client;
use Brian2694\Toastr\Facades\Toastr;

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

    public function sendsms(){
        $sid = getenv("TWILIO_SID");
        $token = getenv("TWILIO_TOKEN");
        $sendernumber = getenv("TWILIO_PHONE");
        $twilio = new Client($sid, $token);

        $message = $twilio->messages->create(
            "+254 708 681664", // to
            [
                "body" =>
                "RENT DUE! Kind reminder to pay your rent!",
                "from" => $sendernumber,
            ]
        );
        print $message->body;
    }
}

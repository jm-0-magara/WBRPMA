<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Notification;

class MpesaNotificationController extends Controller
{
    public function handleMpesaNotification(Request $request)
    {
        Log::info('Mpesa Notification Received:', $request->all());

        // Extract necessary information from the request
        $transactionId = $request->input('TransID');
        $amount = $request->input('TransAmount');
        $phoneNumber = $request->input('MSISDN');
        $transactionTime = $request->input('TransTime');

        // Save the notification data into the database
        Notification::create([
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'phone_number' => $phoneNumber,
            'transaction_time' => $transactionTime,
        ]);

        // Respond to MPESA with a success message
        return response()->json([
            'ResultCode' => 0,
            'ResultDesc' => 'Accepted'
        ]);
        Toastr::success('Mpesa responded successfully :)','Success');
    }
}

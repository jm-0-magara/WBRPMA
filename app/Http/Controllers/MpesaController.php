<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;    

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
        $consumerKey = $this->consumerKey;
        $consumerSecret = $this->consumerSecret;
        $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $response = Http::withBasicAuth($consumerKey,$consumerSecret)->get($url);
        return $response['access_token'];
    }

    public function registerUrl(){
        $accessToken = $this->getAccessToken();
        $url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';
        $ShortCode = 600977;
        $ResponseType = 'Completed';
        $ConfirmationURL = 'https://cfd9-41-90-65-89.ngrok-free.app/pesa/confirmation';
        $ValidationURL = 'https://cfd9-41-90-65-89.ngrok-free.app/pesa/validation';

        $response = Http::withToken($accessToken)->post($url,[
            'ShortCode'=>$ShortCode,
            'ResponseType'=>$ResponseType,
            'ConfirmationURL'=>$ConfirmationURL,
            'ValidationURL'=>$ValidationURL,
        ]);

        return $response;
    }

    public function Simulate(){
        $accessToken = $this->getAccessToken();
        $url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate';
        $ShortCode = 600977;
        $CommandID = 'CustomerBuyGoodsOnline';
        $Amount = 1;
        $Msisdn = 254705912645;
        $BillRefNumber = '';

        $response = Http::withToken($accessToken)->post($url,[
            'ShortCode'=>$ShortCode,
            'CommandID'=>$CommandID,
            'Amount'=>$Amount,
            'Msisdn'=>$Msisdn,
            'BillRefNumber'=>$BillRefNumber
        ]);

        return $response;
    }

    public function Validation(){
        $data = file_get_contents('php://input');
        Storage::dist('local')->put('validation.txt',$data);

        //validation logic
        
        return response()->json([
            'ResultCode'=>0,
            'ResultDesc'=>'Accepted'
        ]);
        /*
        return response()->json([
            'ResultCode'=>'C2B00011',
            'ResultDesc'=>'Rejected'
        ]);
        */
    }

    public function Confirmation(){
        $data = file_get_contents('php://input');
        Storage::dist('local')->put('confirmation.txt',$data);
        //save data to DB

        return response()->json([
            'ResultCode'=>0,
            'ResultDesc'=>'Accepted'
        ]);
    }
}

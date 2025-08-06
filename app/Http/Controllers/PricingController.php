<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Housetypes;
use App\Models\Paymenttypes;
use Illuminate\Support\Facades\Storage;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Session;
use DB;

class PricingController extends Controller
{
    public function updateHousePrice(Request $request)
    {
        $request->validate([
            'houseType' => 'required|string',
            'housePrice' => 'required|numeric',
        ]);

        // Retrieve the rentalNo from the session
        $rentalNo = Session::get('rentalNo');

        // Find the house type in the database
        $houseType = Housetypes::where('houseType', $request->houseType)
                                ->where('rentalNo', $rentalNo)
                                ->first();

        // Check if the house type exists
        if ($houseType) {
            // Update the price
            $houseType->price = $request->housePrice;
            $houseType->save();

            Toastr::success('Price updated successfully :)', 'Success');
            return redirect()->back();
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function addPaymentType(Request $request)
    {
        $request->validate([
            'paymentType' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        // Retrieve the rentalNo from the session
        $rentalNo = Session::get('rentalNo');

        // Check if a payment type with the same name already exists for this rental
        $existingPaymentType = Paymenttypes::where('paymentType', $request->paymentType)
                                          ->where('rentalNo', $rentalNo)
                                          ->first();

        if ($existingPaymentType) {
            Toastr::error('Payment type with that name already exists for this rental.', 'Error');
            return redirect()->back()->withInput();
        }

        // Create a new Paymenttype instance and save it
        $paymentType = new Paymenttypes();
        $paymentType->paymentType = $request->paymentType;
        $paymentType->rentalNo = $rentalNo;
        $paymentType->price = $request->price;
        $paymentType->save();

        Toastr::success('Payment type added successfully :)', 'Success');
        return redirect()->back();
    }

    public function updatePaymentTypePrice(Request $request)
    {
        $request->validate([
            'paymentType' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        // Retrieve the rentalNo from the session
        $rentalNo = Session::get('rentalNo');

        // Find the payment type in the database
        $paymentType = Paymenttypes::where('paymentType', $request->paymentType)
                                  ->where('rentalNo', $rentalNo)
                                  ->first();

        // Check if the payment type exists
        if ($paymentType) {
            // Update the price
            $paymentType->price = $request->price;
            $paymentType->save();

            Toastr::success('Payment type price updated successfully :)', 'Success');
            return redirect()->back();
        } else {
            Toastr::error('Payment type not found for this rental.', 'Error');
            return redirect()->back()->withInput();
        }
    }
}

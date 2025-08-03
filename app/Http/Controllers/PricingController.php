<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Housetypes;
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
}

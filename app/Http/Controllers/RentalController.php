<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rentals;
use App\Models\Paymenttypes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Brian2694\Toastr\Facades\Toastr;
use DB;

class RentalController extends Controller
{

    public function storeNewRental(Request $request)
    {
        DB::beginTransaction();
        $request->validate([
            'rentalName' => 'required|string|max:255',
            'rentalImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required|string|max:1000',
        ]);

        if ($request->hasFile('rentalImage')) {
            $imagePath = $request->file('rentalImage')->store('public/assets/images');
        } else {
            $imagePath = null; 
        }

        $userId = Session::get('user_id');
        if (!$userId) {
            Toastr::error('User not authenticated', 'Error');
            return redirect()->back();
        }

        try {
            $rental = new Rentals;
            $rental->rentalName = $request->rentalName;
            $rental->user_id = $userId;
            $rental->description = $request->description;
            $rental->rentalImage = $imagePath ? Storage::url($imagePath) : null;

            $rental->save();

            DB::commit();

            /* THIS CREATES PROBLEMS!!
            $paymentType = new Paymenttypes;
            $paymentType->paymentType = 'Rent';
            $paymentType->rentalNo = $rental->rentalNo;
            $paymentType->save();

            $paymentType2 = new Paymenttypes;
            $paymentType2->paymentType = 'Rent Deposit';
            $paymentType2->rentalNo = $rental->rentalNo;
            $paymentType2->save();
            */

            Session::put('rentalNo', $rental->rentalNo);
            Session::put('rentalName', $rental->rentalName);
            Session::put('rental_name', $rental->rentalName);

            Toastr::success('New property added successfully :)', 'Success');
            Toastr::success('Default property set :)', 'Success');

            // Redirect to the structure page if the tour is enabled
            if (session('showTour')) {
                return redirect('management/structure/page');
            }

            return redirect()->back();
        }catch(\Exception $e) {
            \Log::info($e);
            DB::rollback();
            Toastr::error('Add new property fail :)','Error');
            return redirect()->back()->withInput();
        }
    }

    public function viewUpdateRental($rentalNo)
    {
        $rental = Rentals::where('rentalNo', $rentalNo)->firstOrFail();
        $rentalName = $rental->rentalName;
        $description = $rental->description;
        $rentalImage = $rental->rentalImage;

        $userId = Session::get('user_id');
        $rentals = Rentals::where('user_id', $userId)->get();

        return view('pages/propertyUpdate',compact('rentalNo','rentalName','description','rentalImage','rentals'));
    }

    public function updateRental(Request $request, $rentalNo){
        $request->validate([
            'rentalName' => 'required|string|max:255',
            'rentalImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required|string|max:1000',
        ]);

        $rental = Rentals::where('rentalNo', $rentalNo)->firstOrFail();

        if ($request->hasFile('rentalImage')) {
            Storage::delete($rental->rentalImage);
            $imagePath = $request->file('rentalImage')->store('public/assets/images');
        } else {
            $imagePath = $rental->rentalImage;
        }

        $userId = Session::get('user_id');
        if (!$userId) {
            Toastr::error('User not authenticated', 'Error');
            return redirect()->back();
        }

        try
        {
        $rental->rentalName = $request->input('rentalName');
        $rental->user_id = $userId;
        $rental->rentalImage = Storage::url($imagePath);
        $rental->description = $request->input('description');
        $rental->save();

        Toastr::success('New property updated successfully :)', 'Success');
        return redirect()->back();
        }catch(\Exception $e) {
            \Log::info($e);
            Toastr::error('Update new property fail :)','Error');
            return redirect()->back()->withInput();
        }
    }

    public function selectRental($rentalNo){
        $rental = Rentals::where('rentalNo', $rentalNo)->firstOrFail();
        $rentalName = $rental->rentalName;
        Session::put('rentalNo', $rentalNo);
        Session::put('rentalName', $rentalName);
        Session::put('rental_name', $rentalName); //THIS IS HOW IT IS SET IN THE LOGIN CONTROLLER
        Toastr::success('Default property set :)', 'Success');
        return redirect()->back();
    }
}

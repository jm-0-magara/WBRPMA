<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Structures;
use App\Models\Housetypes;
use App\Models\Rentals;
use App\Models\Houses;
use Illuminate\Support\Facades\Storage;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Session;
use DB;

class HouseController extends Controller
{
    public function viewAddHouse(){
        $userId = Session::get('user_id');

        $rentalNo = Session::get('rentalNo');
        $houses = Houses::where('rentalNo', $rentalNo)->orderBy('structureName', 'asc')->get();

        $structures = Structures::where('rentalNo',$rentalNo)->orderBy('structureName', 'asc')->get();
        $houseTypes = Housetypes::where('rentalNo', $rentalNo)->get();
        return view('management.addHouse', compact('houses', 'structures', 'houseTypes'));
    }

    public function addHouse(Request $request)
    {
        $request->validate([
            'houseNo' => 'required|string|max:255',
            'structureName' => 'required|string|max:255',
            'houseType' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        $isPaid = false;
        $rentalNo = Session::get('rentalNo');

        $houses = Houses::where('rentalNo', $rentalNo)->get();

        $hse = new Houses();
        $hse->houseNo = $request->houseNo;
        $hse->rentalNo = $rentalNo;
        $hse->structureName = $request->structureName;
        $hse->houseType = $request->houseType;
        $hse->status = $request->status;
        $hse->isPaid = $isPaid;
        $hse->save();


        Toastr::success('House added successfully :)','Success');
        return redirect()->back();
    }

    public function getHouse($houseNo)
    {
        $house = Houses::where('houseNo', $houseNo)->first();
        if (!$house) {
            Toastr::error('House not found.', 'Error');
            return redirect()->back();
        }
        return response()->json($house);
    }

    public function updateHouse(Request $request, $houseNo)
    {
        $request->validate([
            'houseNo' => 'required|string|max:255',
            'structureName' => 'required|string|max:255',
            'houseType' => 'required|string|max:255',
        ]);


        $rentalNo = Session::get('rentalNo');

        $house = Houses::where('houseNo', $houseNo) 
                ->where('rentalNo', $rentalNo)->first();

        if (!$house) {
            Toastr::error('House not found.', 'Error');
            return redirect()->back();
        }
        try{
            $house->structureName = $request->structureName;
            $house->houseType = $request->houseType;
            $house->save();
        } catch (\Exception $e) {
            Toastr::error('An error occurred: ' . $e->getMessage(), 'Error');
            return redirect()->back();
        }

        Toastr::success('House updated successfully :)', 'Success');
        return redirect()->back();
    }

    public function deleteHouse($houseNo)
    {
        $house = Houses::where('houseNo', $houseNo)->first();
        if (!$house) {
            Toastr::error('House not found.', 'Error');
            return redirect()->back();
        }
        $house->delete();
        Toastr::success('House deleted successfully :)', 'Success');
        return redirect()->back();
    }
}

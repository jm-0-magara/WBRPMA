<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StructureTypes;
use App\Models\Structures;
use App\Models\Housetypes;
use Illuminate\Support\Facades\Storage;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Session;
use DB;

class StructureController extends Controller
{
    public function addStructureType(Request $request)
    {
    $request->validate([
        'structureType' => 'required|string|max:255'
    ]);

    $structType = new StructureTypes();
    $structType->structureType = $request->structureType;
    $structType->save();

    Toastr::success('Structure added successfully :)','Success');
    return redirect()->back();
    }

    public function addStructure(Request $request)
    {
    $request->validate([
        'structureName' => 'required|string|max:255',
        'structureType' => 'required|string|max:255',
    ]);

    $rentalNo = Session::get('rentalNo');
    $struct = new Structures();
    $struct->structureName = $request->structureName;
    $struct->structureType = $request->structureType;
    $struct->rentalNo = $rentalNo;
    $struct->save();

    Toastr::success('Grouping added successfully :)','Success');
    return redirect()->back();
    }

    public function addHouseType(Request $request)
    {
    $request->validate([
        'houseType' => 'required|string|max:255',
        'price' => 'required|numeric',
    ]);

    $rentalNo = Session::get('rentalNo');
    $houseType = new HouseTypes();
    $houseType->houseType = $request->houseType;
    $houseType->price = $request->price;
    $houseType->rentalNo = $rentalNo;
    $houseType->save();

    Toastr::success('House Type added successfully :)','Success');
    return redirect()->back();
    }

    public function deleteStructure($structureName)
    {
        $structure = Structures::findOrFail($structureName);
        $structure->delete();

        Toastr::success('Grouping deleted successfully :)', 'Success');

        return redirect()->back();
    }

    public function deleteHouseType($houseType)
    {
        $houseT = Housetypes::findOrFail($houseType);
        $houseT->delete();

        Toastr::success('House Type deleted successfully :)', 'Success');

        return redirect()->back();
    }
}

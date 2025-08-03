<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Employees;
use App\Models\Employeeroles;
use App\Models\Houses;
use App\Models\Rentals;
use App\Models\Housetypes;
use App\Models\Structures;
use App\Models\Structuretypes;

class ManagementController extends Controller
{
    /** employee list */
    public function employeeList()
    {
        $employees = Employees::all();
        $employeeRoles = Employeeroles::all();
        return view('management.employee', compact('employees', 'employeeRoles'));
    }

    /** holiday Page */
    public function housesPage()
    {
        $rentalNo = Session::get('rentalNo');
        $houses = Houses::where('rentalNo', $rentalNo)->orderBy('structureName', 'asc')->get();
        $occupiedHouses = Houses::where('rentalNo', $rentalNo)->where('status', 'Occupied')->count();
        $vacantHouses = Houses::where('rentalNo', $rentalNo)->where('status', 'Vacant')->count();
        $recentlyEvacuatedHouses = Houses::where('rentalNo', $rentalNo)->where('status', 'Recently Evacuated')->count();
        $reservedHouses = Houses::where('rentalNo', $rentalNo)->where('status', 'Reserved')->count();

        return view('management.houses', compact('houses', 'occupiedHouses', 'vacantHouses', 'recentlyEvacuatedHouses', 'reservedHouses'));
    }

    public function structurePage()
    {
        $userId = Session::get('user_id');
        $rentals = Rentals::where('user_id', $userId)->get();

        $rentalNo = Session::get('rentalNo');

        $structureTypes = Structuretypes::all();
        $houses = Houses::where('rentalNo', $rentalNo)->orderBy('structureName', 'asc')->take(5)->get();
        $structures = Structures::where('rentalNo',$rentalNo)->orderBy('structureName', 'asc')->get();
        $houseTypes = Housetypes::where('rentalNo', $rentalNo)->get();
        return view('management.structure', compact('rentals', 'structureTypes', 'structures', 'houseTypes', 'houses'));
    }

    public function pricingPage()
    {
        $rentalNo = Session::get('rentalNo');
        $houseTypes = Housetypes::where('rentalNo', $rentalNo)->get();
        return view('management.pricing', compact('houseTypes'));
    }
    public function getHousePrice($houseType)
    {
    $rentalNo = Session::get('rentalNo');
    $houseType = Housetypes::where('houseType', $houseType)
                            ->where('rentalNo', $rentalNo)
                            ->first();

    if ($houseType) {
        return response()->json(['price' => $houseType->price]);
    } else {
        return response()->json(['price' => null], 404);
    }
    }
    public function maintenancePage()
    {
        return view('management.maintenance');
    }
}

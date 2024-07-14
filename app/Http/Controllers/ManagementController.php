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
        return view('management.houses', compact('houses'));
    }

    public function structurePage()
    {
        $userId = Session::get('user_id');
        $rentals = Rentals::where('user_id', $userId)->get();

        $rentalNo = Session::get('rentalNo');

        $structureTypes = Structuretypes::all();
        $structures = Structures::where('rentalNo',$rentalNo)->orderBy('structureName', 'asc')->get();
        $houseTypes = Housetypes::where('rentalNo', $rentalNo)->get();
        return view('management.structure', compact('rentals', 'structureTypes', 'structures', 'houseTypes'));
    }

    public function pricingPage()
    {
        return view('management.pricing');
    }
}

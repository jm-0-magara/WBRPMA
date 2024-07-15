<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Houses;
use App\Models\Tenants;
use Illuminate\Support\Facades\Storage;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Session;

class ClientController extends Controller
{
    public function clientView()
    {
        $rentalNo = Session::get('rentalNo');
        $tenants = Tenants::join('houses', 'houses.houseNo', '=', 'tenants.houseNo')->where('tenants.rentalNo', $rentalNo)->select('tenants.*', 'houses.*')->get();
        $tenantsPaid = Houses::where('rentalNo', $rentalNo)->where('isPaid', 1)->count();
        $tenantsDue = Houses::where('rentalNo', $rentalNo)->where('isPaid', 0)->count();
        $pendingPayments = Houses::where('rentalNo', $rentalNo)->where('isPaid', 0)->count();

        return view('client.clientView', compact('tenants', 'tenantsPaid', 'tenantsDue', 'pendingPayments'));
    }

    public function show($id)
    {
        $tenant = Tenants::findOrFail($id);
        return response()->json($tenant);
    }

    public function clientAdd()
    {
        $rentalNo = Session::get('rentalNo');
        $houses = Houses::where('rentalNo', $rentalNo)->where(function ($query) {
            $query->where('status', 'Vacant')
                  ->orWhere('status', 'Recently Evacuated');
        })->orderBy('structureName', 'asc')->get();
        $tenants = Tenants::join('houses', 'houses.houseNo', '=', 'tenants.houseNo')->where('tenants.rentalNo', $rentalNo)->select('tenants.*', 'houses.*')->get();

        return view('client.clientAdd', compact('houses', 'rentalNo', 'tenants'));
    }

    public function addTenant(Request $request)
    {
        $request->validate([
            'houseNo' => 'required',
            'names' => 'required|string|max:255',
            'phoneNo' => 'required|string|max:20',
            'IDNO' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('img')) {
            $imagePath = $request->file('img')->store('public/assets/images');
        } else {
            $imagePath = null;
        }

        $rentalNo = Session::get('rentalNo');

        $tenant = new Tenants;
        $tenant->houseNo = $request->houseNo;
        $tenant->names = $request->names;
        $tenant->phoneNo = $request->phoneNo;
        $tenant->IDNO = $request->IDNO;
        $tenant->email = $request->email;
        $tenant->img = Storage::url($imagePath);
        $tenant->rentalNo = $rentalNo;
        $tenant->dateAdded = now()->toDateString();
        $tenant->save();

        $houses = Houses::where('houseNo',$request->houseNo)->firstOrFail();
        $houses->status = 'Occupied';
        $houses->save();

        
        Toastr::success('Tenant added successfully :)','Success');
        return redirect()->back();
    }
}

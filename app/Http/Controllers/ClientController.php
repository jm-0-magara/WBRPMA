<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Houses;
use App\Models\Tenants;
use App\Models\Deletedtenants;
use App\Models\Housetypes;
use App\Models\Payments;
use App\Models\Waterdetails;
use App\Models\Paymenttypes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use DB;


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

    public function show($tenantNo)
    {
        $tenant = Tenants::findOrFail($tenantNo);
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
            'houseNo' => 'required|string|max:255',
            'names' => 'required|string|max:255',
            'phoneNo' => 'required|string|max:20',
            'IDNO' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'dateAdded' => 'required|date|before_or_equal:today',
        ]);

        $rentalNo = Session::get('rentalNo');

        try{
            $tenant = new Tenants;

            if ($request->hasFile('img')) {
                $imagePath = $request->file('img')->store('public/assets/images');
                $tenant->img = Storage::url($imagePath);
            } else {
                $tenant->img = null;
            }

            $tenant->houseNo = $request->houseNo;
            $tenant->names = $request->names;
            $tenant->phoneNo = $request->phoneNo;
            $tenant->IDNO = $request->IDNO;
            $tenant->email = $request->email;
            $tenant->rentalNo = $rentalNo;
            $tenant->dateAdded = $request->dateAdded;
            $tenant->save();

            $houses = Houses::where('houseNo',$request->houseNo)->firstOrFail();
            $houses->status = 'Occupied';
            $houses->save();
        } catch (\Exception $e) {
            Toastr::error('An error occurred: ' . $e->getMessage(), 'Error');
            return redirect()->back();
        }

        
        Toastr::success('Tenant added successfully :)','Success');
        return redirect()->back();
    }

    public function updateClient(Request $request, $tenantNo)
    {
        $request->validate([
            'names' => 'required|string|max:255',
            'phoneNo' => 'required|string|max:20',
            'IDNO' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'dateAdded' => 'nullable|date|before_or_equal:today',
        ]);

        $tenant = Tenants::where('tenantNo', $tenantNo)->first();

        if (!$tenant) {
            Toastr::error('Tenant not found.', 'Error');
            return redirect()->back();
        }

        try{
            if ($request->hasFile('img')) {
                if ($tenant->img) {
                    $oldImagePath = str_replace('/storage/', 'public/', $tenant->img);
                    Storage::delete($oldImagePath);
                }
            $imagePath = $request->file('img')->store('public/assets/images');
            $tenant->img = Storage::url($imagePath);
            }
            if ($request->has('dateAdded')) {
                $tenant->dateAdded = $request->dateAdded;
            }

        $tenant->names = $request->names;
        $tenant->phoneNo = $request->phoneNo;
        $tenant->IDNO = $request->IDNO;
        $tenant->email = $request->email;
        $tenant->save();
        } catch (\Exception $e) {
            Toastr::error('An error occurred: ' . $e->getMessage(), 'Error');
            return redirect()->back();
        }

        Toastr::success('Tenant updated successfully :)', 'Success');
        return redirect()->back();
    }

    public function deleteClient($tenantNo)
    {
        $tenant = Tenants::where('tenantNo', $tenantNo)->first();
        if (!$tenant) {
            Toastr::error('Tenant not found.', 'Error');
            return redirect()->back();
        }

        $houseNo = $tenant->houseNo;
        $rentalNo = $tenant->rentalNo;

        $house = Houses::where('houseNo', $houseNo)->where('rentalNo', $rentalNo)->first();
        $houseRent = 0;
        if ($house) {
            $houseRent = Housetypes::where('rentalNo', $rentalNo)
                                   ->where('houseType', $house->houseType)
                                   ->value('price');
        }


        $dateJoined = \Carbon\Carbon::parse($tenant->dateAdded);
        $dateJoined = \Carbon\Carbon::parse($tenant->dateAdded)->startOfDay();
        $currentDate = \Carbon\Carbon::now()->startOfDay();
        $monthsSinceJoined = $dateJoined->diffInMonths($currentDate); 
        if ($dateJoined->day <= $currentDate->day) {
            $monthsSinceJoined++;
        }
        $monthsSinceJoined = (int) floor($monthsSinceJoined);

        $expectedTotalRent = $houseRent * $monthsSinceJoined;

        $totalRentPaid = Payments::where('rentalno', $rentalNo)
                                 ->where('houseNo', $houseNo)
                                 ->whereRaw('LOWER(paymentType) LIKE ?', ['%rent%'])
                                 ->whereDate('timePaid', '>=', $dateJoined)
                                 ->sum('amount');
        
        $rentDebt = $expectedTotalRent - $totalRentPaid;

        $waterPricePerUnit = Paymenttypes::where('rentalNo', $rentalNo)
                                            ->whereRaw('LOWER(paymentType) LIKE ?', ['%water%'])
                                            ->value('price');
        
        // Get water consumption records since tenant joined
        $waterConsumption = Waterdetails::where('rentalNo', $rentalNo)
                                        ->where('houseNo', $houseNo)
                                        ->whereDate('date', '>=', $dateJoined)
                                        ->orderBy('date', 'asc')
                                        ->get();

        $totalWaterUnits = $waterConsumption->sum('unitsConsumed');
        $totalWaterBill = $totalWaterUnits * $waterPricePerUnit;

        // Get water payments for the tenant
        $waterPayments = Payments::where('rentalno', $rentalNo)
                                    ->where('houseNo', $houseNo)
                                    ->whereRaw('LOWER(paymentType) LIKE ?', ['%water%'])
                                    ->whereDate('timePaid', '>=', $dateJoined)
                                    ->get();
        
        $totalWaterPaid = $waterPayments->sum('amount');
        $waterDebt = $totalWaterBill - $totalWaterPaid;

        //Get deposit balances (rent)
        $allPayments = Payments::where('rentalno', $rentalNo)
                                    ->where('houseNo', $houseNo)
                                    ->whereDate('timePaid', '>=', $dateJoined)
                                    ->orderBy('timePaid', 'asc')
                                    ->get();
        $tenantDepositTotal = $allPayments->filter(function ($payment) {
                                        return ($payment->paymentType) === 'Rent Deposit';
                                    })->sum('amount');
        $tenantDepositBalance = $houseRent - $tenantDepositTotal;

        //get water deposit balance
        $allWaterDepositPayments = Payments::where('rentalno', $rentalNo)
                                    ->where('houseNo', $houseNo)
                                    ->whereRaw('LOWER(paymentType) LIKE ?', ['%water deposit%'])
                                    ->whereDate('timePaid', '>=', $dateJoined)
                                    ->orderBy('timePaid', 'asc')
                                    ->get();
        $waterDepositTotal = $allWaterDepositPayments->sum('amount');
        $waterPricePerUnit = Paymenttypes::where('rentalNo', $rentalNo)
                                    ->whereRaw('LOWER(paymentType) LIKE ?', ['%water deposit%'])
                                    ->value('price');
        $waterDepositBalance = $waterPricePerUnit - $waterDepositTotal;


        $totalDebt = $rentDebt + $waterDebt + $tenantDepositBalance + $waterDepositBalance;


        Deletedtenants::create([
            'deletedTenantNo' => $tenantNo,
            'houseNo' => $houseNo,
            'rentalNo' => $rentalNo,
            'names' => $tenant->names,
            'phoneNo' => $tenant->phoneNo,
            'debt' => $totalDebt,
            'dateDeleted' => now()->toDateString(),
        ]);

        $tenant->delete();

        $house = Houses::where('houseNo', $houseNo)->where('rentalNo', $rentalNo)->first();
        if ($house) {
            $house->status = 'Recently Evacuated';
            $house->save();
        }

        Toastr::success('Tenant deleted successfully :)', 'Success');
        return redirect()->back();
    }
}

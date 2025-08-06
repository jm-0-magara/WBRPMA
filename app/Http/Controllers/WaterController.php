<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Waterdetails;
use App\Models\Paymenttypes;
use App\Models\Houses;
use App\Models\Payments;
use Illuminate\Support\Facades\Storage;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use DB;

class WaterController extends Controller
{
    private function getWaterData($rentalNo, $houseNo = null, $startDate = null, $endDate = null)
    {
        //Used for sending query to add payment
        $paymentType = Paymenttypes::where('rentalNo', $rentalNo)
            ->whereRaw('LOWER(paymentType) LIKE ?', ['%water%'])
            ->value('paymentType');
        // Get the water price per unit for the rental
        $waterPrice = Paymenttypes::where('rentalNo', $rentalNo)
            ->whereRaw('LOWER(paymentType) LIKE ?', ['%water%'])
            ->value('price');
        
        // Build the consumption query with filters
        $query = Waterdetails::where('rentalNo', $rentalNo);
        // Build the payments query with filters (Corrected typo)
        $paymentsQuery = Payments::where('rentalno', $rentalNo)
            ->whereRaw('LOWER(paymentType) LIKE ?', ['%water%']);

        if ($houseNo && $houseNo !== 'all') {
            $query->where('houseNo', $houseNo);
            $paymentsQuery->where('houseno', $houseNo);
        }

        if ($startDate) {
            $query->where('date', '>=', $startDate);
            $paymentsQuery->where('timePaid', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('date', '<=', $endDate);
            $paymentsQuery->where('timePaid', '<=', $endDate);
        }

        $waterDetails = $query->orderBy('date', 'asc')->get();
        $payments = $paymentsQuery->orderBy('timePaid', 'asc')->get();

        // Get a unique, sorted list of all dates from both consumption and payments
        $allDates = collect($waterDetails)->pluck('date')
            ->merge(collect($payments)->pluck('timePaid')->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d');
            }))
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        // Prepare data for the chart, totals, and table
        $amountsPerDate = [];
        $unitsPerDate = [];
        $paymentsPerDate = [];
        $balancePerDate = [];

        foreach ($allDates as $date) {
            $amountsPerDate[$date] = 0;
            $unitsPerDate[$date] = 0;
            $paymentsPerDate[$date] = 0;
            $balancePerDate[$date] = 0;
        }

        // Aggregate consumption data by date
        foreach ($waterDetails as $detail) {
            $amountsPerDate[$detail->date] += $detail->unitsConsumed * ($waterPrice ?? 0);
            $unitsPerDate[$detail->date] += $detail->unitsConsumed;
        }

        // Aggregate payments data by date
        foreach ($payments as $payment) {
            $datePaid = Carbon::parse($payment->timePaid)->format('Y-m-d');
            // Ensure the key exists before adding
            if (isset($paymentsPerDate[$datePaid])) {
                 $paymentsPerDate[$datePaid] += $payment->amount;
            } else {
                 $paymentsPerDate[$datePaid] = $payment->amount;
            }
        }

        // Calculate running balance
        $cumulativeConsumption = 0;
        $cumulativePayments = 0;
        foreach ($allDates as $date) {
            $cumulativeConsumption += $amountsPerDate[$date];
            $cumulativePayments += $paymentsPerDate[$date];
            $balancePerDate[$date] = $cumulativeConsumption - $cumulativePayments;
        }

        // Return all the necessary data
        return [
            'waterDetails' => $waterDetails,
            'payments' => $payments,
            'paymentType' => $paymentType,
            'labels' => array_values($allDates),
            'data' => array_values($amountsPerDate),
            'unitsConsumedData' => array_values($unitsPerDate),
            'paymentsData' => array_values($paymentsPerDate),
            'balanceData' => array_values($balancePerDate),
            'totalAmount' => array_sum(array_values($amountsPerDate)),
            'totalUnits' => array_sum(array_values($unitsPerDate)),
            'totalPayments' => array_sum(array_values($paymentsPerDate)),
            'waterPrice' => $waterPrice ?? 0, // Pass waterPrice as well
        ];
    }

    public function index(Request $request)
    {
        $rentalNo = Session::get('rentalNo');

        if (!$rentalNo) {
            return view('management.water', [
                'waterDetails' => collect(),
                'waterPrice' => 0,
                'houseNos' => collect(),
                'labels' => [],
                'data' => [],
                'unitsConsumedData' => [],
                'payments' => collect(),
                'paymentsData' => [],
                'balanceData' => [],
                'totalAmount' => 0,
                'totalUnits' => 0,
                'totalPayments' => 0,
            ]);
        }
        
        // Fetch houses once and pass to the view
        $houseNos = Houses::where('rentalNo', $rentalNo)->distinct()->pluck('houseNo');
        $initialData = $this->getWaterData($rentalNo);

        return view('management.water', array_merge($initialData, [
            'houseNos' => $houseNos,
        ]));
    }

    /**
     * Handle the AJAX request to filter data.
     */
    public function filter(Request $request)
    {
        $houseNo = $request->input('houseNo');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $rentalNo = Session::get('rentalNo');

        if (!$rentalNo) {
            return response()->json([
                'success' => false,
                'message' => 'No rental property selected.'
            ]);
        }

        $filteredData = $this->getWaterData($rentalNo, $houseNo, $startDate, $endDate);

        return response()->json(array_merge($filteredData, [
            'success' => true,
        ]));
    }
    
    /**
     * Handle the AJAX request to add a new water consumption record.
     */
    public function addWaterDetails(Request $request)
    {
        $request->validate([
            'houseNo' => 'required|string',
            'unitsConsumed' => 'required|numeric|min:0',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $rentalNo = Session::get('rentalNo');
        
        try {
            $waterDetails = new Waterdetails();
            $waterDetails->rentalNo = $rentalNo;
            $waterDetails->houseNo = $request->houseNo;
            $waterDetails->unitsConsumed = $request->unitsConsumed;
            $waterDetails->date = $request->date;
            $waterDetails->notes = $request->notes;
            $waterDetails->save();
            
            Toastr::success('Water detail added successfully :)', 'Success');
        } catch (\Exception $e) {
            Toastr::error('An error occurred: ' . $e->getMessage(), 'Error');
        }
        return redirect()->back();
    }

    public function show($waterConsumedID)
    {
        $waterDetail = Waterdetails::findOrFail($waterConsumedID);
        return response()->json($waterDetail);
    }

    public function updateWaterDetails(Request $request, $waterConsumedID)
    {
        $request->validate([
            'houseNo' => 'required|string',
            'unitsConsumed' => 'required|numeric|min:0',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);
        try {
            $waterDetail = Waterdetails::findOrFail($waterConsumedID);
            $waterDetail->houseNo = $request->houseNo;
            $waterDetail->rentalNo = Session::get('rentalNo');
            $waterDetail->unitsConsumed = $request->unitsConsumed;
            $waterDetail->date = $request->date;
            $waterDetail->notes = $request->notes;
            $waterDetail->save();
            Toastr::success('Water detail updated successfully :)', 'Success');
        }
        catch (\Exception $e) {
            Toastr::error('An error occurred: ' . $e->getMessage(), 'Error');
        }
        return redirect()->back();
    }

    public function deleteWaterDetails($waterConsumedID)
    {
        try {
            $waterDetail = Waterdetails::findOrFail($waterConsumedID);
            $waterDetail->delete();
            Toastr::success('Water detail deleted successfully :)', 'Success');
        } catch (\Exception $e) {
            Toastr::error('An error occurred: ' . $e->getMessage(), 'Error');
        }   
        return redirect()->back();
    }
}

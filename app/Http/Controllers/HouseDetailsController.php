<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Houses;
use App\Models\Tenants;
use App\Models\Payments;
use App\Models\Waterdetails;
use App\Models\Paymenttypes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\Models\Housetypes;
use App\Models\Reservations;
use App\Models\Deletedtenants;
use Illuminate\Support\Str;
use DB;

class HouseDetailsController extends Controller
{
public function showHouseDetails($houseNo)
    {
        $rentalNo = Session::get('rentalNo');
        $house = Houses::where('houseNo', $houseNo)->where('rentalNo', $rentalNo)->firstOrFail();
        $tenant = null;
        $totalRentPaid = 0;
        $houseRent = 0;
        $rentDebt = 0;
        $waterConsumptionData = [];
        $waterConsumptionLabels = [];
        $paymentRecordsChartData = []; // New variable for the chart
        $totalWaterPaid = 0;
        $waterDebt = 0;
        $waterPricePerUnit = 0;
        $paidThisMonth = 0;
        $reservation = null;
        $reservationAmount = 0;
        $deletedTenant = null;
        $unpaidMonths = [];
        $monthlyBalances = [];
        $tenantDepositBalance = 0;
        $tenantDepositPayments = [];
        $tenantDepositTotal = 0;
        $waterDepositBalance = 0;
        $waterDepositPayments = [];
        $waterDepositTotal = 0;

        // Check if the house is occupied to fetch tenant details
        if ($house->status === 'Occupied') {
            $tenant = Tenants::where('houseNo', $houseNo)->where('rentalNo', $rentalNo)->first();

            if ($tenant) {
                // Get house rent from Housetypes model
                $houseRent = Housetypes::where('rentalNo', $rentalNo)
                                       ->where('houseType', $house->houseType)
                                       ->value('price');

                $dateJoined = \Carbon\Carbon::parse($tenant->dateAdded);

                $dateJoined = \Carbon\Carbon::parse($tenant->dateAdded)->startOfDay();
                $currentDate = \Carbon\Carbon::now()->startOfDay();
                $monthsSinceJoined = $dateJoined->diffInMonths($currentDate); 
                if ($dateJoined->day <= $currentDate->day) {
                    $monthsSinceJoined++;
                }
                $monthsSinceJoined = (int) floor($monthsSinceJoined);

                // Get all rent payments for the tenant
                $rentPayments = Payments::where('rentalno', $rentalNo)
                                        ->where('houseNo', $houseNo)
                                        ->whereRaw('LOWER(paymentType) LIKE ?', ['rent'])
                                        ->whereRaw('LOWER(paymentType) NOT LIKE ?', ['%deposit%'])
                                        ->whereDate('timePaid', '>=', $dateJoined)
                                        ->get();

                // Calculate total rent paid
                $totalRentPaid = $rentPayments->sum('amount');

                // Calculate expected total rent
                $expectedTotalRent = $houseRent * $monthsSinceJoined;

                // Calculate rent debt
                $rentDebt = $expectedTotalRent - $totalRentPaid;

                // Check for current month's rent payment to update isPaid
                $currentMonth = Carbon::now()->month;
                $currentYear = Carbon::now()->year;

                $paidThisMonth = $rentPayments->filter(function($payment) use ($currentMonth, $currentYear) {
                    return Carbon::parse($payment->timePaid)->month == $currentMonth && Carbon::parse($payment->timePaid)->year == $currentYear;
                })->sum('amount');

                if ($paidThisMonth >= $houseRent || $rentDebt <= 0) {
                    $house->isPaid = true;
                    $house->save();
                } else {
                    $house->isPaid = false;
                    $house->save();
                }

                // Get water price 
                $waterPricePerUnit = Paymenttypes::where('rentalNo', $rentalNo)
                                                 ->whereRaw('LOWER(paymentType) LIKE ?', ['%water%'])                                                 
                                                 ->whereRaw('LOWER(paymentType) NOT LIKE ?', ['%deposit%'])
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
                                         ->whereRaw('LOWER(paymentType) NOT LIKE ?', ['%deposit%'])
                                         ->whereDate('timePaid', '>=', $dateJoined)
                                         ->get();
                
                $totalWaterPaid = $waterPayments->sum('amount');
                $waterDebt = $totalWaterBill - $totalWaterPaid;

                // Prepare data for charts
                // Water Consumption
                $waterConsumptionData = $waterConsumption->map(function ($item) {
                    return $item->unitsConsumed;
                })->toArray();

                $waterConsumptionLabels = $waterConsumption->map(function ($item) {
                    return Carbon::parse($item->date)->format('M Y');
                })->toArray();

                // Payment Records - Now displays ALL payments and their types
                $allPayments = Payments::where('rentalno', $rentalNo)
                                       ->where('houseNo', $houseNo)
                                       ->whereDate('timePaid', '>=', $dateJoined)
                                       ->orderBy('timePaid', 'asc')
                                       ->get();
                
                //DEPOSIT LOGIC
                $tenantDepositIsPaid = Tenants::where('rentalNo', $rentalNo)
                                          ->where('houseNo', $houseNo)
                                          ->value('rentDepositPaid');
                $tenantDepositTotal = $allPayments->filter(function ($payment) {
                                        return ($payment->paymentType) === 'Rent Deposit';
                                    })->sum('amount');
                if (!$tenantDepositIsPaid) {
                    $tenantDepositBalance = $houseRent - $tenantDepositTotal;
                    if ($tenantDepositBalance <= 0) {
                        $tenantDepositIsPaid = true;
                        Tenants::where('rentalNo', $rentalNo)
                               ->where('houseNo', $houseNo)
                               ->update(['rentDepositPaid' => true]);
                    } else {
                        $tenantDepositIsPaid = false;
                    }
                }else{ //Incase record is deleted
                    $tenantDepositBalance = 0;
                    if($tenantDepositTotal < $houseRent) {
                        $tenantDepositIsPaid = false;
                        Tenants::where('rentalNo', $rentalNo)
                               ->where('houseNo', $houseNo)
                               ->update(['rentDepositPaid' => false]);
                    }
                }
                $tenantDepositPayments = $allPayments->filter(function ($payment) {
                                            return ($payment->paymentType) === 'Rent Deposit';
                                        })->values();

                //WATER DEPOSIT LOGIC
                $waterDepositIsPaid = Tenants::where('rentalNo', $rentalNo)
                                          ->where('houseNo', $houseNo)
                                          ->value('waterDepositPaid');
                $waterPricePerUnit = Paymenttypes::where('rentalNo', $rentalNo)
                                                ->whereRaw('LOWER(paymentType) LIKE ?', ['%water deposit%'])
                                                ->value('price');
                $allWaterDepositPayments = Payments::where('rentalno', $rentalNo)
                                                ->where('houseNo', $houseNo)
                                                ->whereRaw('LOWER(paymentType) LIKE ?', ['%water deposit%'])
                                                ->whereDate('timePaid', '>=', $dateJoined)
                                                ->get();
                $waterDepositTotal = $allWaterDepositPayments->sum('amount');
                if (!$waterDepositIsPaid) {
                    $waterDepositBalance = $waterPricePerUnit - $waterDepositTotal;
                    if ($waterDepositBalance <= 0) {
                        $waterDepositIsPaid = true;
                        Tenants::where('rentalNo', $rentalNo)
                               ->where('houseNo', $houseNo)
                               ->update(['waterDepositPaid' => true]);
                    }
                } else { //Incase record is deleted
                    $waterDepositBalance = 0;
                    if ($waterDepositTotal < $waterPricePerUnit) {
                        $waterDepositIsPaid = false;
                        Tenants::where('rentalNo', $rentalNo)
                                ->where('houseNo', $houseNo)   
                                ->update(['waterDepositPaid' => false]);
                    }
                }
                $waterDepositPayments = $allWaterDepositPayments->values();

                
                //CHART LOGIC
                $paymentRecordsChartData = $allPayments->map(function ($payment) {
                    return [
                        'x' => Carbon::parse($payment->timePaid)->format('Y-m-d H:i:s'),
                        'y' => $payment->amount,
                        'title' => ucwords($payment->paymentType) // Add payment type to data point
                    ];
                });

                //FOR UNPAID MONTHS AND MONTHLY BALANCES
                $moveInDate = Carbon::parse($tenant->dateAdded)->startOfMonth();
                $today = Carbon::now()->startOfMonth();

                // Create list of all months between move-in and today
                $allMonths = [];
                for ($date = $moveInDate->copy(); $date->lte($today); $date->addMonth()) {
                    $allMonths[] = $date->format('Y-m');
                }

                // Get all rent payments (grouped by month) for this tenant
                $monthlyPayments = Payments::where('rentalno', $rentalNo)
                    ->where('houseNo', $houseNo)
                    ->whereRaw('LOWER(paymentType) LIKE ?', ['%rent%'])
                    ->whereRaw('LOWER(paymentType) NOT LIKE ?', ['%deposit%'])
                    ->whereDate('timePaid', '>=', $moveInDate)
                    ->get()
                    ->groupBy(function ($payment) {
                        return Carbon::parse($payment->timePaid)->format('Y-m');
                    })
                    ->map(function ($monthGroup) {
                        return $monthGroup->sum('amount');
                    });

                foreach ($allMonths as $month) {
                    $paidAmount = $monthlyPayments[$month] ?? 0;
                    $monthlyBalances[$month] = $houseRent - $paidAmount;
                }

                // Find unpaid months
                $paidMonths = array_keys($monthlyPayments->toArray());
                $unpaidMonthsArray = array_diff($allMonths, $paidMonths);

                // Convert unpaid months to readable format
                $unpaidMonths = array_map(function ($month) {
                    return Carbon::createFromFormat('Y-m', $month)->format('F Y');
                }, $unpaidMonthsArray);
            }
        }
        elseif ($house->status == 'Reserved') {
            // Fetch reservation details for reserved houses
            $reservation = Reservations::where('houseNo', $houseNo)->where('rentalNo', $rentalNo)
                ->first();
            if ($reservation) {
                $reservationAmount = $reservation->amountDeposited;
                $houseRent = Housetypes::where('rentalNo', $rentalNo)
                                       ->where('houseType', $house->houseType)
                                       ->value('price');
                
                $rentDebt = $houseRent - $reservationAmount; // Calculate rent debt based on reservation amount
            }
        }
        elseif ($house->status == 'Recently Evacuated') {
            $deletedTenant = Deletedtenants::where('houseNo', $houseNo)
                                           ->where('rentalNo', $rentalNo)
                                           ->orderBy('dateDeleted', 'desc')
                                           ->first();
        }

        //For adding a new payment
        $paymentTypes = Paymenttypes::where('rentalNo', $rentalNo)->pluck('paymentType');

        // Pass data to the view
        return view('management.house_details', compact(
            'house',
            'tenant',
            'reservation',
            'deletedTenant',
            'totalRentPaid',
            'houseRent',
            'rentDebt',
            'reservationAmount',
            'waterConsumptionData',
            'waterConsumptionLabels',
            'paymentRecordsChartData', // Pass the new chart data
            'totalWaterPaid',
            'waterDebt',
            'waterPricePerUnit',
            'paidThisMonth',
            'paymentTypes',
            'tenantDepositBalance',
            'tenantDepositPayments',
            'tenantDepositTotal',
            'waterDepositBalance',
            'waterDepositTotal',
            'waterDepositPayments',
            'monthlyBalances',
            'unpaidMonths' // Pass the unpaid months data
        ));
    }

    public function filterHousePayments(Request $request, $houseNo)
    {
        $rentalNo = Session::get('rentalNo');

        // Get filter parameters from the request
        $paymentTypeFilter = $request->input('paymentType', 'all');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $dateJoined = Tenants::where('houseNo', $houseNo)
                                 ->where('rentalNo', $rentalNo)
                                 ->value('dateAdded');

        // Base query for payments for the specific house
        $query = Payments::where('rentalNo', $rentalNo)
                         ->where('houseNo', $houseNo)
                         ->whereDate('timePaid', '>=', $dateJoined);

        // Apply filters
        if ($paymentTypeFilter && $paymentTypeFilter !== 'all') {
            $query->where('paymentType', $paymentTypeFilter);
        }

        if ($startDate) {
            $query->where('timePaid', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('timePaid', '<=', Carbon::parse($endDate)->endOfDay());
        }

        $payments = $query->orderBy('timePaid', 'asc')->get();

        // Prepare data for the chart
        $paymentRecordsChartData = $payments->map(function ($payment) {
            return [
                'x' => Carbon::parse($payment->timePaid)->timestamp * 1000,
                'y' => $payment->amount,
                'title' => $payment->paymentType,
                'paymentMethod' => $payment->paymentMethod,
            ];
        })->toArray();

        // Return JSON response with filtered payments and chart data
        return response()->json([
            'payments' => $payments,
            'chartData' => $paymentRecordsChartData,
        ]);
    }
}

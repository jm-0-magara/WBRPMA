<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payments; 
use App\Models\Expenditures;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use DB;

class FinancialController extends Controller
{
    private function getFinancialData($userID, $startDate = null, $endDate = null)
    {
        // Base query for payments
        $paymentsQuery = Payments::where('userID', $userID); // Assuming userID is on Payments model
        // Base query for expenditures
        $expendituresQuery = Expenditures::where('userID', $userID);

        // Apply date filters to both queries
        if ($startDate) {
            $paymentsQuery->where('timePaid', '>=', $startDate);
            $expendituresQuery->where('timePaid', '>=', $startDate);
        }

        if ($endDate) {
            // Ensure endDate includes the entire day
            $paymentsQuery->where('timePaid', '<=', Carbon::parse($endDate)->endOfDay());
            $expendituresQuery->where('timePaid', '<=', Carbon::parse($endDate)->endOfDay());
        }

        $payments = $paymentsQuery->get();
        $expenditures = $expendituresQuery->get();

        // Collect all unique dates from both payments and expenditures
        $allDates = $payments->pluck('timePaid')->map(function ($date) {
            return Carbon::parse($date)->format('Y-m-d');
        })->merge($expenditures->pluck('timePaid')->map(function ($date) {
            return Carbon::parse($date)->format('Y-m-d');
        }))->unique()->sort()->values()->toArray();

        // Initialize data arrays for each date
        $grossProfitData = array_fill_keys($allDates, 0);
        $expenditureData = array_fill_keys($allDates, 0);
        $netProfitData = array_fill_keys($allDates, 0);

        // Aggregate payments (Gross Profit) by date
        foreach ($payments as $payment) {
            $date = Carbon::parse($payment->timePaid)->format('Y-m-d');
            $grossProfitData[$date] += $payment->amount;
        }

        // Aggregate expenditures by date
        foreach ($expenditures as $expenditure) {
            $date = Carbon::parse($expenditure->timePaid)->format('Y-m-d');
            $expenditureData[$date] += $expenditure->amount;
        }

        // Calculate Net Profit for each date
        foreach ($allDates as $date) {
            $netProfitData[$date] = $grossProfitData[$date] - $expenditureData[$date];
        }

        return [
            'labels' => array_values($allDates),
            'grossProfitSeries' => array_values($grossProfitData),
            'expenditureSeries' => array_values($expenditureData),
            'netProfitSeries' => array_values($netProfitData),
        ];
    }

    /**
     * Display the financial overview page with the chart.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $userId = Session::get('userID'); // Get userID from session
        $userID = User::where('user_id', $userId)->value('id'); 
        if (!$userID) {
            // If no user ID is available, return an empty view
            return view('dashboard.home', [
                'labels' => [],
                'grossProfitSeries' => [],
                'expenditureSeries' => [],
                'netProfitSeries' => [],
            ]);
        }

        $initialData = $this->getFinancialData($userID);

        return view('dashboard.home', $initialData);
    }

    /**
     * Handle the AJAX request to filter financial data by date range.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function filter(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $userId = Session::get('userID');
        $userID = User::where('user_id', $userId)->value('id'); 
        if (!$userID) {
            return response()->json([
                'success' => false,
                'message' => 'No user ID available.'
            ]);
        }

        $filteredData = $this->getFinancialData($userID, $startDate, $endDate);

        return response()->json(array_merge($filteredData, [
            'success' => true,
        ]));
    }
}

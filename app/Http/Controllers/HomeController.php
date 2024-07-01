<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payments;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\Models\Rentals;
use App\Models\Employees;
use App\Models\Employeeroles;
use App\Models\Expenditures;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
{
    $currentMonth = Carbon::now()->month;
    $currentYear = Carbon::now()->year;

    // Calculate total gross profit for the current month
    $totalGrossProfit = Payments::whereYear('timePaid', $currentYear)
        ->whereMonth('timePaid', $currentMonth)
        ->sum('amount');

    // Get the previous month's gross profit
    $previousMonth = Carbon::now()->subMonth()->month;
    $previousMonthGrossProfit = Payments::whereYear('timePaid', $currentYear)
        ->whereMonth('timePaid', $previousMonth)
        ->sum('amount');

    // Calculate the percentage increase in gross profit
    $percentageIncrease = $previousMonthGrossProfit > 0 ? 
        (($totalGrossProfit - $previousMonthGrossProfit) / $previousMonthGrossProfit) * 100 : 0;

    // Calculate total expenditure for the current month
    $totalExpenditure = Expenditures::whereYear('timePaid', $currentYear)
        ->whereMonth('timePaid', $currentMonth)
        ->sum('amount');

    // Get the previous month's expenditure
    $previousMonthExpenditure = Expenditures::whereYear('timePaid', $currentYear)
        ->whereMonth('timePaid', $previousMonth)
        ->sum('amount');

    // Calculate the percentage increase in expenditure
    $percentageDecrease = $previousMonthExpenditure > 0 ? 
        (($totalExpenditure - $previousMonthExpenditure) / $previousMonthExpenditure) * 100 : 0;

    $userId = Session::get('user_id');
    $rentals = Rentals::where('user_id', $userId)->get();
    $employees = Employees::all();
    $employeeRoles = EmployeeRoles::all();

    $expenditures = DB::table('expenditures')
        ->select(DB::raw('SUM(amount) as total_amount, MONTHNAME(timePaid) as month'))
        ->groupBy('month')
        ->orderBy(DB::raw('STR_TO_DATE(month, "%M")'), 'asc')
        ->get();

    $months = $expenditures->pluck('month');
    $amounts = $expenditures->pluck('total_amount');

    //For the last division of recent transactions
    $expendituresTransactions = DB::table('expenditures')
                      ->select('expenditureType as name', 'amount', 'timePaid', DB::raw('"expenditure" as type'))
                      ->orderBy('timePaid', 'desc')
                      ->limit(7)
                      ->get();

    $paymentsTransactions = DB::table('payments')
                  ->join('tenants', 'payments.houseNo', '=', 'tenants.houseNo')
                  ->select('tenants.names as name', 'payments.amount', 'payments.timePaid', DB::raw('"payment" as type'))
                  ->orderBy('timePaid', 'desc')
                  ->limit(7)
                  ->get();

    $mergedData = $expendituresTransactions->merge($paymentsTransactions)->sortByDesc('timePaid')->take(7);

    return view('dashboard.home', compact('rentals', 'totalGrossProfit', 'totalExpenditure', 'employees', 'employeeRoles', 'percentageIncrease', 'percentageDecrease', 'months', 'amounts'))->with('transactions', $mergedData);
}

    public function getPayments()
    {
        // Fetch gross profits
        $payments = DB::table('payments')
            ->select(DB::raw('SUM(amount) as total_amount, MONTHNAME(timePaid) as month'))
            ->groupBy('month')
            ->orderBy(DB::raw('STR_TO_DATE(month, "%M")'), 'asc')
            ->get();

        // Fetch expenditures
        $expenditures = DB::table('expenditures')
            ->select(DB::raw('SUM(amount) as total_expenditure, MONTHNAME(timePaid) as month'))
            ->groupBy('month')
            ->orderBy(DB::raw('STR_TO_DATE(month, "%M")'), 'asc')
            ->get();

        // Calculate net profits
        $netProfits = [];
        foreach ($payments as $payment) {
            $expenditure = $expenditures->firstWhere('month', $payment->month);
            $total_expenditure = $expenditure ? $expenditure->total_expenditure : 0;
            $netProfits[] = [
                'month' => $payment->month,
                'total_amount' => $payment->total_amount,
                'net_profit' => $payment->total_amount - $total_expenditure
            ];
        }

        return response()->json($netProfits);
    }

}

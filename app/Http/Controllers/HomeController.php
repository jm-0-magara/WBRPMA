<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payments;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\Models\Rentals;
use App\Models\Employees;
use App\Models\User;
use App\Models\Houses;
use App\Models\Employeeroles;
use App\Models\Expenditures;
use App\Models\Maintenances;
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
  
    $rentalNo = Session::get('rentalNo');
    $userID = Session::get('user_id');
    $userId = User::where('user_id', $userID)->value('id');

    $reservedHouses = Houses::where('rentalNo', $rentalNo)
        ->where('status', 'Reserved')
        ->count();

    $dueHouses = Houses::where('rentalNo', $rentalNo)
        ->where('isPaid', false)
        ->count();

    $paidHouses = Houses::where('rentalNo', $rentalNo)
        ->where('isPaid', true)
        ->count();

    $currentMonth = Carbon::now()->month;
    $currentYear = Carbon::now()->year;

    // Calculate total gross profit for the current month
    $totalGrossProfit = Payments::whereYear('timePaid', $currentYear)
        ->whereMonth('timePaid', $currentMonth)
        ->where('rentalNo', $rentalNo)
        ->sum('amount');

    // Get the previous month's gross profit
    $previousMonth = Carbon::now()->subMonth()->month;
    $previousMonthGrossProfit = Payments::whereYear('timePaid', $currentYear)
        ->whereMonth('timePaid', $previousMonth)
        ->where('rentalNo', $rentalNo)
        ->sum('amount');

    // Calculate the percentage increase in gross profit
    $percentageIncrease = 0;
    if ($previousMonthGrossProfit > 0) {
        $percentageIncrease = (($totalGrossProfit - $previousMonthGrossProfit) / $previousMonthGrossProfit) * 100;
    }

    // Calculate total expenditure for the current month
    $totalExpenditure = Expenditures::whereYear('timePaid', $currentYear)
        ->whereMonth('timePaid', $currentMonth)
        ->where('userID', $userId)
        ->sum('amount');

    // Get the previous month's expenditure
    $previousMonthExpenditure = Expenditures::whereYear('timePaid', $currentYear)
        ->whereMonth('timePaid', $previousMonth)
        ->where('userID', $userId)
        ->sum('amount');

    // Calculate the percentage increase in expenditure
    $percentageDecrease = 0;
    if ($previousMonthExpenditure > 0) {
        $percentageDecrease = (($previousMonthExpenditure - $totalExpenditure) / $previousMonthExpenditure) * 100;
    }

    //Calculate for net profit
    $netProfitPercentageIncrease = 0;
    $previousMonthNetProfit = $previousMonthGrossProfit - $previousMonthExpenditure;
    $totalNetProfit = $totalGrossProfit - $totalExpenditure;
    if ($previousMonthNetProfit != 0){
        $netProfitPercentageIncrease = (($previousMonthNetProfit - $totalNetProfit) / $previousMonthNetProfit) * 100;
    }

    $rentals = Rentals::where('user_id', $userID)->get();
    $employees = Employees::where('userID', $userId)
                ->join('rentals', 'employees.rentalNo', '=', 'rentals.rentalNo') 
                ->get();
    $employeeRoles = EmployeeRoles::all();

    $maintenances = Maintenances::where('rentalNo', $rentalNo)
                             ->orderBy('maintenanceDate', 'desc')
                             ->limit(7)
                             ->get();

    $expenditures = DB::table('expenditures')
    ->select(DB::raw('
        SUM(amount) as total_amount,
        MONTHNAME(timePaid) as month,
        YEAR(timePaid) as year
    '))
    ->where('userID', $userId)
    ->groupBy('year', 'month')
    ->orderBy('year', 'asc')
    ->orderBy(DB::raw('MONTH(timePaid)'), 'asc')
    ->get();


    $months = $expenditures->pluck('month');
    $amounts = $expenditures->pluck('total_amount');

    //For the last division of recent transactions
    $expendituresTransactions = DB::table('expenditures')
                      ->select('expenditureType as name', 'amount', 'timePaid', DB::raw('"expenditure" as type'))
                      ->where('userID', $userId)
                      ->orderBy('timePaid', 'desc')
                      ->limit(7)
                      ->get();

    $paymentsTransactions = DB::table('payments')
                  ->join('tenants', 'payments.houseNo', '=', 'tenants.houseNo')
                  ->select(DB::raw('CONCAT(payments.houseNo, " - ", tenants.names) as name'), 'payments.amount', 'payments.timePaid', DB::raw('"payment" as type'))
                  ->where('payments.rentalNo', $rentalNo)
                  ->orderBy('timePaid', 'desc')
                  ->limit(7)
                  ->get();

    $mergedData = $expendituresTransactions->merge($paymentsTransactions)->sortByDesc('timePaid')->take(7);

    return view('dashboard.home', compact('rentals', 'reservedHouses', 'dueHouses', 'paidHouses', 'totalGrossProfit', 'totalExpenditure', 'employees', 'employeeRoles', 'percentageIncrease', 'percentageDecrease', 'totalNetProfit', 'netProfitPercentageIncrease', 'months', 'amounts', 'maintenances'))->with('transactions', $mergedData);
}

    public function getPayments()
    {
        $rentalNo = Session::get('rentalNo');
        // Fetch gross profits
        $payments = DB::table('payments')
            ->select(DB::raw('SUM(amount) as total_amount, MONTHNAME(timePaid) as month, YEAR(timePaid) as year'))
            ->where('rentalNo', $rentalNo)
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy(DB::raw('MONTH(timePaid)'), 'asc')
            ->get();

        // Fetch expenditures
        $userId = Session::get('user_id');
        $userID = User::where('user_id', $userId)->value('id');
        $expenditures = DB::table('expenditures')
            ->select(DB::raw('SUM(amount) as total_expenditure, MONTHNAME(timePaid) as month, YEAR(timePaid) as year'))
            ->where('userID', $userID)
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy(DB::raw('MONTH(timePaid)'), 'asc')
            ->get();

        // Calculate net profits
        $netProfits = [];
        foreach ($payments as $payment) {
            $expenditure = $expenditures->first(function ($exp) use ($payment) {
                return $exp->month === $payment->month && $exp->year === $payment->year;
            });

            $total_expenditure = $expenditure ? $expenditure->total_expenditure : 0;

            $netProfits[] = [
                'month' => $payment->month . ' ' . $payment->year,
                'total_amount' => $payment->total_amount,
                'net_profit' => $payment->total_amount - $total_expenditure
            ];
        }

        return response()->json($netProfits);
    }

}

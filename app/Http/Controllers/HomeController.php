<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payments;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\Models\Rentals;

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

        $totalGrossProfit = Payments::whereYear('timePaid', $currentYear)
            ->whereMonth('timePaid', $currentMonth)
            ->sum('amount');

        $userId = Session::get('user_id');
        $rentals = Rentals::where('user_id', $userId)->get();

        return view('dashboard.home', compact('rentals', 'totalGrossProfit'));
    }

}

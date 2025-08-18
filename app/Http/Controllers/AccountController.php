<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Rentals;
use App\Models\Tenants;
use Carbon\Carbon;
use App\Models\Payments;
use App\Models\Expenditures;
use Brian2694\Toastr\Facades\Toastr;
use DB;

class AccountController extends Controller
{
    /** page account profile */
    public function index()
    {
        //CAREFUL!! I HAPPENED TO HAVE MISSNAMED THE COLUMNS
        //AND THE RELATIONSHIPS TOO
        //THIS APPLIES TO THE USERID VARIABLES
        $rentalNo = Session::get('rentalNo');
        $userId = Session::get('user_id');
        $rentals = Rentals::where('user_id', $userId)->get();
        $userID = User::where('user_id', $userId)->value('id');

         // Fetch payments and expenditures for the user
        $payments = DB::table('payments')
            ->join('rentals', 'payments.rentalNo', '=', 'rentals.rentalNo')
            ->where('rentals.user_id', $userId)
            ->get();

        $expenditures = DB::table('expenditures')
            ->where('userID', $userID)
            ->get();

        // Group payments and expenditures by month and year
        $monthlyData = [];

        foreach ($payments as $payment) {
            $date = Carbon::parse($payment->timePaid)->format('Y-m');
            if (!isset($monthlyData[$date])) {
                $monthlyData[$date] = [
                    'payments' => 0,
                    'expenditures' => 0,
                ];
            }
            $monthlyData[$date]['payments'] += $payment->amount;
        }

        foreach ($expenditures as $expenditure) {
            $date = Carbon::parse($expenditure->timePaid)->format('Y-m');
            if (!isset($monthlyData[$date])) {
                $monthlyData[$date] = [
                    'payments' => 0,
                    'expenditures' => 0,
                ];
            }
            $monthlyData[$date]['expenditures'] += $expenditure->amount;
        }

        // Sort data by date
        krsort($monthlyData);
        $monthlyData = array_slice($monthlyData, 0, 7, true); // Get the last 7 months
        ksort($monthlyData);

        $chartLabels = [];
        $paymentAmounts = [];
        $expenditureAmounts = [];
        $mostProfitableMonth = null;
        $maxNetProfit = -INF;

        foreach ($monthlyData as $date => $data) {
            $carbonDate = Carbon::parse($date);
            $chartLabels[] = $carbonDate->format('F Y');
            $paymentAmounts[] = $data['payments'];
            $expenditureAmounts[] = $data['expenditures'];

            $netProfit = $data['payments'] - $data['expenditures'];
            if ($netProfit > $maxNetProfit) {
                $maxNetProfit = $netProfit;
                $mostProfitableMonth = [
                    'month' => $carbonDate->format('F'),
                    'year' => $carbonDate->format('Y'),
                    'net_profit' => $netProfit,
                ];
            }
        }

        //For the client cards
        $currentRentalNo = Session::get('rentalNo');

        $tenants = DB::table('tenants')
                        ->join('rentals', 'tenants.rentalNo', '=', 'rentals.rentalNo')
                        ->join('houses', 'tenants.houseNo', '=', 'houses.houseNo')
                        ->select('tenants.*', 'houses.isPaid', 'rentals.rentalName')
                        ->where('rentals.user_id', $userId)
                        ->get();
        
        //KWA PROFILE
        $rentalsCount = $rentals->count();
        $tenantsCount = $tenants->count();

        return view('pages.account', compact('rentals', 'rentalsCount', 'tenantsCount', 'chartLabels', 'paymentAmounts', 'expenditureAmounts', 'mostProfitableMonth', 'tenants', 'currentRentalNo'));
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        $userId = $user->user_id;

        if ($user->avatar) {
            Storage::delete($user->avatar);
        }

        $path = $request->file('avatar')->store('public/assets/images');

        $user->avatar = Storage::url($path);
        $user->save();

        Session::put('avatar', $user->avatar);

        return redirect()->back()->with('success', 'Profile image updated successfully.');
        Toastr::success('Image Updated successfully :)','Success');
    }

    public function viewPropertyInput()
    {
        $userId = Session::get('user_id');
        $rentals = Rentals::where('user_id', $userId)->get();
        return view('pages.propertyInput', compact('rentals'));
    }
}

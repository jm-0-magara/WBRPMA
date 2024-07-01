<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Rentals;
use DB;

class AccountController extends Controller
{
    /** page account profile */
    public function index()
{
    $userId = Session::get('user_id');
    $rentals = Rentals::where('user_id', $userId)->get();

    // Fetch payments data grouped by month
    $payments = DB::table('payments')
        ->select(DB::raw('SUM(amount) as total_amount, MONTHNAME(timePaid) as month'))
        ->groupBy('month')
        ->orderBy(DB::raw('STR_TO_DATE(month, "%M")'), 'asc')
        ->get();

    // Fetch expenditures data grouped by month
    $expenditures = DB::table('expenditures')
        ->select(DB::raw('SUM(amount) as total_amount, MONTHNAME(timePaid) as month'))
        ->groupBy('month')
        ->orderBy(DB::raw('STR_TO_DATE(month, "%M")'), 'asc')
        ->get();

    // Extract data for JavaScript
    $months = $payments->pluck('month')->merge($expenditures->pluck('month'))->unique()->sort(function ($a, $b) {
        return strtotime($a) - strtotime($b);
    })->values();

    $paymentAmounts = $months->map(function ($month) use ($payments) {
        return $payments->firstWhere('month', $month)->total_amount ?? 0;
    });

    $expenditureAmounts = $months->map(function ($month) use ($expenditures) {
        return $expenditures->firstWhere('month', $month)->total_amount ?? 0;
    });

    return view('pages.account', compact('rentals', 'months', 'paymentAmounts', 'expenditureAmounts'));
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

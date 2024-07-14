<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payments;
use App\Models\Expenditures;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;


class FinancialController extends Controller
{
    public function showPayments(Request $request)
{
    $rentalNo = Session::get('rentalNo');
    $query = DB::table('payments')
        ->where('rentalNo', $rentalNo)
        ->select('paymentID', 'houseNo', 'paymentType', 'amount', 'timeRecorded', 'timePaid', 'paymentMethod')
        ->orderBy('timePaid', 'desc');

    // ADD ALSO PROPERTY FILTERING 
    if ($request->filled('houseNo')) {
        $query->where('houseNo', $request->houseNo);
    }

    if ($request->filled('paymentType')) {
        $query->where('paymentType', $request->paymentType);
    }

    if ($request->filled('startDate') && $request->filled('endDate')) {
        $query->whereBetween('timePaid', [$request->startDate, $request->endDate]);
    }

    $payments = $query->get();

    return view('finance.payments', ['payments' => $payments]);
}

public function downloadPdf(Request $request)
{
    $rentalNo = Session::get('rentalNo');
    $query = DB::table('payments')
        ->where('rentalNo', $rentalNo)
        ->select('paymentID', 'houseNo', 'paymentType', 'amount', 'timeRecorded', 'timePaid', 'paymentMethod')
        ->orderBy('timePaid', 'desc');

    if ($request->filled('houseNo')) {
        $query->where('houseNo', $request->houseNo);
    }

    if ($request->filled('paymentType')) {
        $query->where('paymentType', $request->paymentType);
    }

    if ($request->filled('startDate') && $request->filled('endDate')) {
        $query->whereBetween('timeRecorded', [$request->startDate, $request->endDate]);
    }

    $payments = $query->get();

    $pdf = Pdf::loadView('finance.paymentPDF.pdf', ['payments' => $payments]);

    return $pdf->download('finance.paymentPDF.pdf');
}

public function showExpenditures(Request $request)
{
    $user_ID = Session::get('user_id');
    $userID = User::where('user_id', $user_ID)->firstOrFail();
    $query = DB::table('expenditures')
        ->where('userID', $userID)
        ->select('expenditureID', 'expenditureType', 'amount', 'timeRecorded', 'timePaid')
        ->orderBy('timePaid', 'desc');

    if ($request->filled('expenditureType')) {
        $query->where('expenditureType', $request->expenditureType);
    }

    if ($request->filled('startDate') && $request->filled('endDate')) {
        $query->whereBetween('timePaid', [$request->startDate, $request->endDate]);
    }

    $expenditures = $query->get();

    return view('finance.expenditures', ['expenditures' => $expenditures]);
}

public function downloadExpenditurePdf(Request $request)
{
    $user_ID = Session::get('user_id');
    $userID = Expenditures::where('user_id', $user_ID)->firstOrFail();
    $query = DB::table('expenditures')
        ->where('userID', $userID)
        ->select('expenditureID', 'expenditureType', 'amount', 'timeRecorded', 'timePaid')
        ->orderBy('timePaid', 'desc');

    if ($request->filled('expenditureType')) {
        $query->where('expenditureType', $request->expenditureType);
    }

    if ($request->filled('startDate') && $request->filled('endDate')) {
        $query->whereBetween('timeRecorded', [$request->startDate, $request->endDate]);
    }

    $expenditures = $query->get();

    $pdf = Pdf::loadView('finance.expenditurePDF.pdf', ['expenditures' => $expenditures]);

    return $pdf->download('finance.expenditurePDF.pdf');
}
}

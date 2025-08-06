<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payments;
use App\Models\Expenditures;
use App\Models\User;
use App\Models\Houses;
use App\Models\Paymenttypes;
use Illuminate\Support\Facades\Session;
use Brian2694\Toastr\Facades\Toastr;
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

    //FOR PAYMENT TYPES AND ADDING PAYMENT
    $query2 = DB::table('paymenttypes')
        ->where('rentalNo', $rentalNo)
        ->select('paymentType');

    $query3 = DB::table('houses')
        ->where('rentalNo', $rentalNo)
        ->select('houseNo');
      
    $payments = $query->get();
    $paymentTypes = $query2->get();
    $houses = $query3->get();

    return view('finance.payments', [
        'payments' => $payments,
        'paymentTypes' => $paymentTypes,
        'houses' => $houses
    ]);
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
public function addPaymentType(Request $request)
{
    $request->validate([
        'paymentType' => 'required|string|max:255',
        'price' => 'required|numeric'
    ]);

    $rentalNo = Session::get('rentalNo');

    $paymentTypes = new Paymenttypes();
    $paymentTypes->paymentType = $request->paymentType;
    $paymentTypes->rentalNo = $rentalNo;
    $paymentTypes->price = $request->price;
    $paymentTypes->save();

    Toastr::success('Payment Type added successfully :)','Success');
    return redirect()->back();
}

public function addPayment(Request $request){
    DB::beginTransaction();
    $rentalNo = Session::get('rentalNo');
        $request->validate([
            'paymentID' => 'required|string|max:255',
            'houseNo' => 'required|string|max:255',
            'paymentType' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'timePaid' => 'required|date_format:Y-m-d',
            'paymentMethod' => 'required|string|max:255'
        ]);

        try{
        $payment = new Payments();
        $payment->paymentID = $request->paymentID;
        $payment->houseNo = $request->houseNo;
        $payment->rentalNo = $rentalNo;
        $payment->paymentType = $request->paymentType;
        $payment->amount = $request->amount;
        $payment->timePaid = $request->timePaid;
        $payment->paymentMethod = $request->paymentMethod;

        $payment->save();

        DB::commit();

        Toastr::success('New payment added successfully :)', 'Success');
        
        return redirect()->back();
        }catch(\Exception $e) {
        \Log::info($e);
        DB::rollback();
        Toastr::error('Add new payment fail :)','Error');
        return redirect()->back()->withInput();
        }
    }

    public function addExpenditure(Request $request){
    DB::beginTransaction();
    $user_ID = Session::get('user_id');
    $userID = User::where('user_id', $user_ID)->firstOrFail();
        $request->validate([
            'expenditureID' => 'required|string|max:255',
            'expenditureType' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'timePaid' => 'required|date_format:Y-m-d',
        ]);

        try{
        $expenditure = new Expenditures();
        $expenditure->expenditureID = $request->expenditureID;
        $expenditure->expenditureType = $request->expenditureType;
        $expenditure->userID = $userID->user_id;
        $expenditure->amount = $request->amount;
        $expenditure->timePaid = $request->timePaid;

        $expenditure->save();

        DB::commit();

        Toastr::success('New expenditure added successfully :)', 'Success');
        
        return redirect()->back();
        }catch(\Exception $e) {
        \Log::info($e);
        DB::rollback();
        Toastr::error('Add new expenditure fail :)','Error');
        return redirect()->back()->withInput();
        }
}
}

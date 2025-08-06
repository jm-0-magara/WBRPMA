<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payments;
use App\Models\Paymenttypes;
use App\Models\Houses;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use DB;

class PaymentController extends Controller
{
    private function getPaymentData($rentalNo, $paymentTypeFilter = null, $houseNoFilter = null, $startDate = null, $endDate = null)
    {
        // Base query for payments
        $query = Payments::where('rentalno', $rentalNo);

        // Apply filters if provided
        if ($paymentTypeFilter && $paymentTypeFilter !== 'all') {
            $query->where('paymentType', $paymentTypeFilter);
        }

        if ($houseNoFilter && $houseNoFilter !== 'all') {
            $query->where('houseNo', $houseNoFilter);
        }

        if ($startDate) {
            $query->where('timePaid', '>=', $startDate);
        }

        if ($endDate) {
            // Ensure endDate includes the entire day
            $query->where('timePaid', '<=', Carbon::parse($endDate)->endOfDay());
        }

        $payments = $query->orderBy('timePaid', 'asc')->get();

        // Get all available payment types and house numbers for filter dropdowns
        $paymentTypes = Paymenttypes::where('rentalNo', $rentalNo)->pluck('paymentType');
        $houseNos = Houses::where('rentalNo', $rentalNo)->pluck('houseNo');

        // Prepare data for the chart
        $labels = []; // Dates for the X-axis
        $data = [];   // Total amount paid for each date

        // Aggregate payments by date for the chart
        $paymentsByDate = $payments->groupBy(function($date) {
            return Carbon::parse($date->timePaid)->format('Y-m-d');
        });

        foreach ($paymentsByDate as $date => $dailyPayments) {
            $labels[] = $date;
            $data[] = $dailyPayments->sum('amount');
        }

        // Calculate total amount paid
        $totalAmountPaid = $payments->sum('amount');

        return [
            'payments' => $payments,
            'paymentTypes' => $paymentTypes,
            'houseNos' => $houseNos,
            'labels' => $labels,
            'data' => $data,
            'totalAmountPaid' => $totalAmountPaid,
        ];
    }

    /**
     * Display a listing of the payments.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $rentalNo = Session::get('rentalNo');

        if (!$rentalNo) {
            // If no rental property is selected, return an empty view
            return view('finance.payments', [
                'payments' => collect(),
                'paymentTypes' => collect(),
                'houseNos' => collect(),
                'labels' => [],
                'data' => [],
                'totalAmountPaid' => 0,
            ]);
        }

        $initialData = $this->getPaymentData($rentalNo);

        return view('finance.payments', array_merge($initialData, [
            'paymentTypes' => Paymenttypes::where('rentalNo', $rentalNo)->pluck('paymentType'),
            'houseNos' => Houses::where('rentalNo', $rentalNo)->pluck('houseNo'),
        ]));
    }

    /**
     * Handle the AJAX request to filter payments.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function filter(Request $request)
    {
        $paymentTypeFilter = $request->input('paymentType');
        $houseNoFilter = $request->input('houseNo');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $rentalNo = Session::get('rentalNo');

        if (!$rentalNo) {
            return response()->json([
                'success' => false,
                'message' => 'No rental property selected.'
            ]);
        }

        $filteredData = $this->getPaymentData($rentalNo, $paymentTypeFilter, $houseNoFilter, $startDate, $endDate);

        // Format timePaid for display in the table
        $filteredData['payments']->map(function ($payment) {
            $payment->timePaidFormatted = Carbon::parse($payment->timePaid)->format('Y-m-d');
            return $payment;
        });

        return response()->json(array_merge($filteredData, [
            'success' => true,
        ]));
    }

    /**
     * Handle the AJAX request to add a new payment record.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addPayment(Request $request)
    {
        $request->validate([
            'paymentID' => 'required|integer|unique:payments,paymentID', // Ensure paymentID is unique
            'houseNo' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'paymentType' => 'required|string',
            'paymentMethod' => 'required|string',
            'timePaid' => 'required|date',
        ]);

        $rentalNo = Session::get('rentalNo');

        if (!$rentalNo) {
            Toastr::error('No rental property selected.', 'Error');
            return redirect()->back();
        }

        try {
            $payment = new Payments();
            $payment->paymentID = $request->paymentID;
            $payment->houseNo = $request->houseNo;
            $payment->rentalno = $rentalNo;
            $payment->amount = $request->amount;
            $payment->paymentType = $request->paymentType;
            $payment->paymentMethod = $request->paymentMethod;
            $payment->timePaid = $request->timePaid;
            $payment->timeRecorded = Carbon::now(); // Set current time for timeRecorded
            $payment->save();

            Toastr::success('Payment added successfully :)', 'Success');
        } catch (\Exception $e) {
            Toastr::error('An error occurred: ' . $e->getMessage(), 'Error');
        }
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified payment.
     *
     * @param int $paymentID
     * @return \Illuminate\Http\JsonResponse
     */
    public function showPayment($paymentID)
    {
        $payment = Payments::findOrFail($paymentID);
        return response()->json($payment);
    }

    /**
     * Update the specified payment in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $paymentID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePayment(Request $request, $paymentID)
    {
        $request->validate([
            'houseNo' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'paymentType' => 'required|string',
            'paymentMethod' => 'required|string',
            'timePaid' => 'required|date',
        ]);

        try {
            $payment = Payments::findOrFail($paymentID);
            $payment->houseNo = $request->houseNo;
            $payment->rentalno = Session::get('rentalNo'); // Ensure rentalNo is consistent
            $payment->amount = $request->amount;
            $payment->paymentType = $request->paymentType;
            $payment->paymentMethod = $request->paymentMethod;
            $payment->timePaid = $request->timePaid;
            // timeRecorded is not updated here as it's the initial record time
            $payment->save();

            Toastr::success('Payment updated successfully :)', 'Success');
        } catch (\Exception $e) {
            Toastr::error('An error occurred: ' . $e->getMessage(), 'Error');
        }
        return redirect()->back();
    }

    /**
     * Remove the specified payment from storage.
     *
     * @param int $paymentID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deletePayment($paymentID)
    {
        try {
            $payment = Payments::findOrFail($paymentID);
            $payment->delete();
            Toastr::success('Payment deleted successfully :)', 'Success');
        } catch (\Exception $e) {
            Toastr::error('An error occurred: ' . $e->getMessage(), 'Error');
        }
        return redirect()->back();
    }
}

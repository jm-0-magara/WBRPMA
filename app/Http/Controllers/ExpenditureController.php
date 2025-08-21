<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expenditures;
use App\Models\Expendituretypes;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Models\Maintenances;
use App\Models\User;
use App\Models\Houses;
use DB;

class ExpenditureController extends Controller
{
    private function getExpenditureData($userID, $expenditureTypeFilter = null, $startDate = null, $endDate = null)
    {
        // Base query for expenditures
        $query = Expenditures::where('userID', $userID);

        // Apply filters if provided
        if ($expenditureTypeFilter && $expenditureTypeFilter !== 'all') {
            $query->where('expenditureType', $expenditureTypeFilter);
        }

        if ($startDate) {
            $query->where('timePaid', '>=', $startDate);
        }

        if ($endDate) {
            // Ensure endDate includes the entire day
            $query->where('timePaid', '<=', Carbon::parse($endDate)->endOfDay());
        }

        $expenditures = $query->orderBy('timePaid', 'asc')->get();

        // Get all available expenditure types for filter dropdowns
        $expenditureTypes = Expendituretypes::pluck('expenditureType'); 

        // Prepare data for the chart
        $labels = []; // Dates for the X-axis
        $data = [];   // Total amount spent for each date

        // Aggregate expenditures by date for the chart
        $expendituresByDate = $expenditures->groupBy(function($date) {
            return Carbon::parse($date->timePaid)->format('Y-m-d');
        });

        foreach ($expendituresByDate as $date => $dailyExpenditures) {
            $labels[] = $date;
            $data[] = $dailyExpenditures->sum('amount');
        }

        // For the bar chart
        $monthlyExpendituresData = [];
        $monthlyLabels = [];

        // Aggregate expenditures by month for the bar chart
        $expendituresByMonth = $expenditures->groupBy(function($date) {
            return Carbon::parse($date->timePaid)->format('Y-m');
        });

        foreach ($expendituresByMonth as $month => $monthlyGroup) {
            $monthlyLabels[] = $month;
            $monthlyExpendituresData[] = $monthlyGroup->sum('amount');
        }

        // Calculate total amount spent
        $totalAmountSpent = $expenditures->sum('amount');

        //FOR ADDING MAINTENANCE RECORDS
        $rentalNo = Session::get('rentalNo');
        $houses = Houses::where('rentalNo', $rentalNo)->get();

        return [
            'expenditures' => $expenditures,
            'expenditureTypes' => $expenditureTypes,
            'labels' => $labels,
            'data' => $data,
            'totalAmountSpent' => $totalAmountSpent,
            'monthlyLabels' => $monthlyLabels,
            'monthlyExpendituresData' => $monthlyExpendituresData,
            'houses' => $houses,
        ];
    }

    /**
     * Display a listing of the expenditures.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $userId = Session::get('user_id'); // Get userID from session
        $userID = User::where('user_id', $userId)->value('id'); // Get the actual user ID from the database

        if (!$userID) {
            // If no user ID is selected/available, return an empty view
            return view('finance.expenditures', [
                'expenditures' => collect(),
                'expenditureTypes' => collect(),
                'labels' => [],
                'data' => [],
                'totalAmountSpent' => 0,
                'monthlyLabels' => [],
                'monthlyExpendituresData' => [],
            ]);
        }

        $initialData = $this->getExpenditureData($userID);

        return view('finance.expenditures', array_merge($initialData, [
            'expenditureTypes' => Expendituretypes::pluck('expenditureType'),
        ]));
    }

    /**
     * Handle the AJAX request to filter expenditures.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function filter(Request $request)
    {
        $expenditureTypeFilter = $request->input('expenditureType');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $userId = Session::get('user_id');
        $userID = User::where('user_id', $userId)->value('id'); // Get the actual user ID from the database

        if (!$userID) {
            return response()->json([
                'success' => false,
                'message' => 'No user ID available.'
            ]);
        }

        $filteredData = $this->getExpenditureData($userID, $expenditureTypeFilter, $startDate, $endDate);

        // Format timePaid for display in the table
        $filteredData['expenditures']->map(function ($expenditure) {
            $expenditure->timePaidFormatted = Carbon::parse($expenditure->timePaid)->format('Y-m-d');
            return $expenditure;
        });

        return response()->json(array_merge($filteredData, [
            'success' => true,
        ]));
    }

    /**
     * Handle the AJAX request to add a new expenditure record.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addExpenditure(Request $request)
    {
        $request->validate([
            'expenditureID' => 'required|string|unique:expenditures,expenditureID', // Ensure expenditureID is unique
            'expenditureType' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'timePaid' => 'required|date|before_or_equal:today', 
        ]);

        $userId = Session::get('user_id');
        
        if (!$userId) {
            Toastr::error('No user ID available.', 'Error');
            return redirect()->back();
        }

        $userID = User::where('user_id', $userId)->value('id');
        $rentalNo = Session::get('rentalNo');

        if ($request->expenditureType === 'Maintenance') {
            $request->validate([
                'houseNo' => 'required|string|max:255',
                'maintenanceDescription' => 'nullable|string',
            ]);
        }

        try {
            $expenditure = new Expenditures();
            $expenditure->expenditureID = $request->expenditureID;
            $expenditure->expenditureType = $request->expenditureType;
            $expenditure->userID = $userID;
            $expenditure->amount = $request->amount;
            $expenditure->timePaid = $request->timePaid;
            $expenditure->save();

            if ($request->expenditureType === 'Maintenance') {
                $maintenance = new Maintenances();
                $maintenance->houseNo = $request->houseNo;
                $maintenance->rentalNo = $rentalNo;
                $maintenance->maintenanceDate = $request->timePaid;
                $maintenance->amount = $request->amount;
                $maintenance->maintenanceDescription = $request->maintenanceDescription;
                $maintenance->save();
            }

            Toastr::success('Expenditure added successfully :)', 'Success');
        } catch (\Exception $e) {
            Toastr::error('An error occurred: ' . $e->getMessage(), 'Error');
        }
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified expenditure.
     *
     * @param int $expenditureID
     * @return \Illuminate\Http\JsonResponse
     */
    public function showExpenditure($expenditureID)
    {
        $expenditure = Expenditures::findOrFail($expenditureID);
        return response()->json($expenditure);
    }

    /**
     * Update the specified expenditure in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $expenditureID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateExpenditure(Request $request, $expenditureID)
    {
        $request->validate([
            'expenditureType' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'timePaid' => 'required|date|before_or_equal:today', 
        ]);

        $userId = Session::get('user_id');
        $userID = User::where('user_id', $userId)->value('id'); // Get the actual user ID from the database

        try {
            $expenditure = Expenditures::findOrFail($expenditureID);

            if ($expenditure->expenditureType === 'Maintenance' && $request->expenditureType === 'Maintenance') {
                $maintenance = Maintenances::where('maintenanceDate', $expenditure->timePaid)
                    ->where('amount', $expenditure->amount)
                    ->first();
                if ($maintenance) {
                    $maintenance->amount = $request->amount;
                    $maintenance->maintenanceDate = $request->timePaid;
                    $maintenance->save();
                }
            }

            $expenditure->expenditureType = $request->expenditureType;
            $expenditure->userID = $userID; // Ensure userID is consistent
            $expenditure->amount = $request->amount;
            $expenditure->timePaid = $request->timePaid;
            // timeRecorded is not updated here as it's the initial record time
            $expenditure->save();

            Toastr::success('Expenditure updated successfully :)', 'Success');
        } catch (\Exception $e) {
            Toastr::error('An error occurred: ' . $e->getMessage(), 'Error');
        }
        return redirect()->back();
    }

    /**
     * Remove the specified expenditure from storage.
     *
     * @param int $expenditureID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteExpenditure($expenditureID)
    {
        try {
            $expenditure = Expenditures::findOrFail($expenditureID);

            if ($expenditure->expenditureType === 'Maintenance') {
                $maintenance = Maintenances::where('maintenanceDate', $expenditure->timePaid)
                    ->where('amount', $expenditure->amount)
                    ->first();
                if ($maintenance) {
                    $maintenance->delete();
                }
            }
            $expenditure->delete();
            Toastr::success('Expenditure deleted successfully :)', 'Success');
        } catch (\Exception $e) {
            Toastr::error('An error occurred: ' . $e->getMessage(), 'Error');
        }
        return redirect()->back();
    }
}

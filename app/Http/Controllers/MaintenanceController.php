<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Houses;
use App\Models\Maintenances;
use App\Models\Expenditures;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Brian2694\Toastr\Facades\Toastr;

class MaintenanceController extends Controller
{
    public function index()
    {
        // Get the current rental ID from the session
        $rentalNo = Session::get('rentalNo');

        // Fetch all houses for the current rental to populate the dropdown filter
        $houses = Houses::where('rentalNo', $rentalNo)->get();

        // Fetch all maintenance records for the current rental initially
        $maintenances = Maintenances::where('rentalNo', $rentalNo)
                                    ->orderBy('maintenanceDate', 'desc')
                                    ->get();

        // Calculate the initial total expenditure for the entire rental
        $totalExpenditure = $maintenances->sum('amount');

        // Prepare initial data for the chart (monthly expenditure)
        $monthlyData = Maintenances::where('rentalNo', $rentalNo)
                                ->selectRaw('DATE_FORMAT(maintenanceDate, "%Y-%m") as month, SUM(amount) as total_amount')
                                ->groupBy('month')
                                ->orderBy('month')
                                ->get();
        
        $months = $monthlyData->pluck('month')->toArray();
        $amounts = $monthlyData->pluck('total_amount')->toArray();
        
        return view('management.maintenance', compact('houses', 'maintenances', 'totalExpenditure', 'months', 'amounts'));
    }

    /**
     * Filter maintenance data based on user selection via AJAX.
     */
    public function filter(Request $request)
    {
        $rentalNo = Session::get('rentalNo');
        $houseNo = $request->input('houseNo');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Start with a base query for the current rental
        $query = Maintenances::where('rentalNo', $rentalNo);

        // Apply filters if they are present
        if ($houseNo && $houseNo !== 'all') {
            $query->where('houseNo', $houseNo);
        }

        if ($startDate) {
            $query->where('maintenanceDate', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('maintenanceDate', '<=', $endDate);
        }

        $maintenances = $query->orderBy('maintenanceDate', 'desc')->get();
        $totalExpenditure = $maintenances->sum('amount');
        
        // Prepare chart data for the filtered results
        $monthlyData = $query->selectRaw('DATE_FORMAT(maintenanceDate, "%Y-%m") as month, SUM(amount) as total_amount')
                            ->groupBy('month')
                            ->orderBy('month')
                            ->get();

        $months = $monthlyData->pluck('month')->toArray();
        $amounts = $monthlyData->pluck('total_amount')->toArray();

        return response()->json([
            'maintenances' => $maintenances,
            'totalExpenditure' => $totalExpenditure,
            'chartLabels' => $months,
            'chartData' => $amounts,
        ]);
    }

    public function addMaintenance(Request $request)
    {
        $request->validate([
            'houseNo' => 'required|string|max:255',
            'maintenanceDate' => 'required|date|before_or_equal:today',
            'amount' => 'required|numeric|min:0',
            'maintenanceDescription' => 'nullable|string',
        ]);

        $rentalNo = Session::get('rentalNo');
        $userId = Session::get('user_id');
        $userID = User::where('user_id', $userId)->value('id');

        $maintenance = new Maintenances();
        $maintenance->houseNo = $request->houseNo;
        $maintenance->rentalNo = $rentalNo;
        $maintenance->maintenanceDate = $request->maintenanceDate;
        $maintenance->amount = $request->amount;
        $maintenance->maintenanceDescription = $request->maintenanceDescription;
        $maintenance->save();

        $expenditure = new Expenditures();
        $expenditure->expenditureID = rand(0, 1000000);
        $expenditure->expenditureType = 'Maintenance';
        $expenditure->userID = $userID;
        $expenditure->amount = $request->amount;
        $expenditure->timePaid = $request->maintenanceDate;
        $expenditure->save();

        Toastr::success('Maintenance record added successfully.', 'Success');
        return redirect()->back();
    }

    /**
     * Retrieve a specific maintenance record for updating.
     */
    public function getMaintenanceDetails(Request $request)
    {
        $maintenanceNo = $request->input('maintenanceNo');
        $maintenance = Maintenances::find($maintenanceNo);
        
        if (!$maintenance) {
            return response()->json(['error' => 'Maintenance record not found.'], 404);
        }

        return response()->json($maintenance);
    }

    /**
     * Update an existing maintenance record.
     */
    public function updateMaintenance(Request $request, $maintenanceNo)
    {
        $request->validate([
            'houseNo' => 'required|string|max:255',
            'maintenanceDate' => 'required|date|before_or_equal:today',
            'amount' => 'required|numeric|min:0',
            'maintenanceDescription' => 'nullable|string',
        ]);

        //I USED THIS FOR DEBUGGING
        /*\Log::debug('update-Maintenance-debug', [
            'maintenanceNo' => $maintenanceNo,
            'houseNo' => $request->houseNo,
            'maintenanceDate' => $request->maintenanceDate,
            'amount' => $request->amount,
            'maintenanceDescription' => $request->maintenanceDescription,
        ]);*/
        
    
        $maintenance = Maintenances::find($request->maintenanceNo);
    
        if (!$maintenance) {
            Toastr::error('Maintenance record not found.', 'Error');
            return redirect()->back();
        }
    
        try{
            $maintenance->houseNo = $request->houseNo;
            $maintenance->maintenanceDate = $request->maintenanceDate;
            $maintenance->amount = $request->amount;
            $maintenance->maintenanceDescription = $request->maintenanceDescription;
            $maintenance->save();
        } catch (\Exception $e) {
            Toastr::error('An error occurred: ' . $e->getMessage(), 'Error');
            return redirect()->back();
        }
    
        Toastr::success('Maintenance record updated successfully.', 'Success');
        return redirect()->back();
    }

    public function deleteMaintenance($maintenanceNo)
    {
        try {
            $maintenance = Maintenances::findOrFail($maintenanceNo);

            $maintenance->delete();
            Toastr::success('Maintenance record deleted successfully :)', 'Success');
        } catch (\Exception $e) {
            Toastr::error('An error occurred while deleting the record.', 'Error');
        }
        
        return redirect()->back();
    }
}

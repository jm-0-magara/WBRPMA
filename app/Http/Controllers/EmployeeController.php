<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employeeroles;
use App\Models\Employees;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Session;
use DB;

class EmployeeController extends Controller
{
    public function addEmployeeRole(Request $request)
    {
    $request->validate([
        'employeeRole' => 'required|string|max:255'
    ]);

    $role = new Employeeroles();
    $role->employeeRole = $request->employeeRole;
    $role->save();

    Toastr::success('Role added successfully :)','Success');
    return redirect()->back();
    }


    public function addEmployee(Request $request)
    {
        DB::beginTransaction();
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'employeeRole' => 'required|string|max:255',
            'phoneNo' => 'required|string|max:15',
            'salary' => 'required|numeric',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $rentalNo = Session::get('rentalNo');
        if (!$rentalNo) {
            Toastr::error('Rental not selected', 'Error');
            return redirect()->back();
        }

        $userId = Session::get('user_id');
        $userID = User::where('user_id', $userId)->value('id');

        try{
            $employee = new Employees();

            if ($request->hasFile('img')) {
                $imagePath = $request->file('img')->store('public/assets/images');
                $employee->img = Storage::url($imagePath);
            } else {
                $employee->img = null;
            }

            $employee->fname = $request->fname;
            $employee->lname = $request->lname;
            $employee->rentalNo = $rentalNo;
            $employee->userID = $userID;
            $employee->employeeRole = $request->employeeRole;
            $employee->phoneNo = $request->phoneNo;
            $employee->salary = $request->salary;

            $employee->save();

            DB::commit();

            Toastr::success('New employee added successfully :)', 'Success');
            
            return redirect()->back();
        }catch(\Exception $e) {
        \Log::info($e);
        DB::rollback();
        Toastr::error('Add new employee fail :)','Error');
        return redirect()->back()->withInput();
        }
    }

    public function viewUpdateEmployee($employeeNo){

        $employees = Employees::all();
        $employeeRoles = Employeeroles::all();

        $employee = Employees::where('employeeNo', $employeeNo)->firstOrFail();
        $fname = $employee->fname;
        $lname = $employee->lname;
        $employeeRole = $employee->employeeRole;
        $phoneNo = $employee->phoneNo;
        $salary = $employee->salary;
        $img = $employee->img;

        $showUpdateEmployee = true; //flag

        return view('management/employee',compact('employees','employeeRoles','employeeNo','fname','lname','employeeRole','phoneNo', 'salary', 'img'));
    }

    public function updateEmployee(Request $request, $employeeNo){
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'employeeRole' => 'required|string|max:255',
            'phoneNo' => 'required|string|max:15',
            'salary' => 'required|numeric',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $employee = Employees::where('employeeNo', $employeeNo)->firstOrFail();

        DB::beginTransaction();
        try
        {
            if ($request->hasFile('img')) {
                if ($employee->img) {
                    $oldImagePath = str_replace('/storage/', 'public/', $employee->img);
                    Storage::delete($oldImagePath);
                }
            $imagePath = $request->file('img')->store('public/assets/images');
            $employee->img = Storage::url($imagePath);
            }
        $employee->fname = $request->input('fname');
        $employee->lname = $request->input('lname');
        $employee->employeeRole = $request->input('employeeRole');
        $employee->phoneNo = $request->input('phoneNo');
        $employee->salary = $request->input('salary');
        $employee->save();

        DB::commit();

        Toastr::success('Employee updated successfully :)', 'Success');
        return redirect()->back();
        }catch(\Exception $e) {
            \Log::info($e);
            DB::rollback();
            Toastr::error('Update employee fail :)','Error');
            return redirect()->back()->withInput();
        }
    }

    public function deleteEmployee($employeeNo)
    {
        // Find the employee by ID and delete
        $employee = Employees::findOrFail($employeeNo);
        $employee->delete();

        Toastr::success('Employee deleted successfully :)', 'Success');

        return redirect()->back();
    }
}

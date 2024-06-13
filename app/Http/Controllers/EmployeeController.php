<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employeeroles;
use App\Models\Employees;
use Illuminate\Support\Facades\Storage;
use Brian2694\Toastr\Facades\Toastr;
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

        if ($request->hasFile('img')) {
            $imagePath = $request->file('img')->store('public/assets/images');
        } else {
            $imagePath = null;
        }

        try{
        $employee = new Employees();
        $employee->fname = $request->fname;
        $employee->lname = $request->lname;
        $employee->employeeRole = $request->employeeRole;
        $employee->phoneNo = $request->phoneNo;
        $employee->salary = $request->salary;
        $employee->img = Storage::url($imagePath);

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

        if ($request->hasFile('img')) {
            Storage::delete($employee->img);
            $imagePath = $request->file('img')->store('public/assets/images');
        } else {
            $imagePath = $employee->img;
        }


        try
        {
        $employee->fname = $request->input('fname');
        $employee->lname = $request->input('lname');
        $employee->employeeRole = $request->input('employeeRole');
        $employee->phoneNo = $request->input('phoneNo');
        $employee->salary = $request->input('salary');
        $employee->img = Storage::url($imagePath);;
        $employee->save();

        Toastr::success('Employee updated successfully :)', 'Success');
        return redirect()->back();
        }catch(\Exception $e) {
            \Log::info($e);
            Toastr::error('Update employee fail :)','Error');
            return redirect()->back()->withInput();
        }
    }

    public function deleteEmployee($employeeNo)
    {
        // Find the employee by ID and delete
        $employee = Employee::findOrFail($employeeNo);
        $employee->delete();

        Toastr::success('Employee deleted successfully :)', 'Success');

        return redirect()->back();
    }
}

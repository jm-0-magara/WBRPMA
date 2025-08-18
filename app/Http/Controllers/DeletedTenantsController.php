<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deletedtenants;
use Illuminate\Support\Facades\Session;


class DeletedTenantsController extends Controller
{
    public function viewDeletedTenants()
    {
        $rentalNo = Session::get('rentalNo');
        $deletedTenants = Deletedtenants::where('rentalNo', $rentalNo)->get();

        return view('pages.history', compact('deletedTenants'));
    }
}

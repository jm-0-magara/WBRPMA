<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function clientView()
    {
        return view('client.client-view');
    }
    public function clientAdd()
    {
        return view('client.client-add');
    }
}

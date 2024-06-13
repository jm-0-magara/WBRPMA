<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use DB;
use Auth;
use Session;
use Carbon\Carbon;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Rentals;


class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            $username = $request->email;
            $password = $request->password;
            $dt = Carbon::now();
            $todayDate = $dt->toDayDateTimeString();

            if (Auth::attempt(['email' => $username, 'password' => $password])) {
                $user = Auth::user();

                // Set session data
                Session::put('name', $user->name);
                Session::put('email', $user->email);
                Session::put('user_id', $user->user_id);
                Session::put('join_date', $user->join_date);
                Session::put('last_login', $user->join_date);
                Session::put('phone_number', $user->phone_number);
                Session::put('status', $user->status);
                Session::put('role_name', $user->role_name);
                Session::put('avatar', $user->avatar);
                Session::put('position', $user->position);
                Session::put('department', $user->department);
                Session::put('rental_name', $user->rental_name);

                // Update last login time
                $user->last_login = $todayDate;
                $user->save();

                // Retrieve user's rentals and flash to session
                $rentals = Rentals::where('user_id', $user->user_id)->get();
                $request->session()->flash('rentals', $rentals);

                Toastr::success('Login successfully :)', 'Success');
                return redirect()->intended('/home');
            } else {
                Toastr::error('fail, WRONG USERNAME OR PASSWORD :)', 'Error');
                return redirect('login');
            }
        } catch (\Exception $e) {
            \Log::info($e);
            DB::rollback();
            Toastr::error('login fail :)', 'Error');
            return redirect()->back();
        }
    }

    public function logoutPage()
    {
        return view('auth.logout');
    }

    public function logout(Request $request)
    {
        // forget login session
        $request->session()->forget('name');
        $request->session()->forget('email');
        $request->session()->forget('user_id');
        $request->session()->forget('join_date');
        $request->session()->forget('last_login');
        $request->session()->forget('phone_number');
        $request->session()->forget('rental_name');
        $request->session()->forget('status');
        $request->session()->forget('role_name');
        $request->session()->forget('avatar');
        $request->session()->forget('position');
        $request->session()->forget('department');
        $request->session()->flush();
        Auth::logout();
        Toastr::success('Logout successfully :)', 'Success');
        return redirect('logout/page');
    }
}

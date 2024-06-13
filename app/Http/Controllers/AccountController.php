<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Rentals;

class AccountController extends Controller
{
    /** page account profile */
    public function index()
    {
        $userId = Session::get('user_id');
        $rentals = Rentals::where('user_id', $userId)->get();
        return view('pages.account', compact('rentals'));
    }
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        $userId = $user->user_id;

        if ($user->avatar) {
            Storage::delete($user->avatar);
        }

        $path = $request->file('avatar')->store('public/assets/images');

        $user->avatar = Storage::url($path);
        $user->save();

        Session::put('avatar', $user->avatar);

        return redirect()->back()->with('success', 'Profile image updated successfully.');
        Toastr::success('Image Updated successfully :)','Success');
    }
    public function viewPropertyInput()
    {
        $userId = Session::get('user_id');
        $rentals = Rentals::where('user_id', $userId)->get();
        return view('pages.propertyInput', compact('rentals'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
// use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\role;

class LoginController extends Controller
{
    public function viewLogin(){
        return view('auth/login');
    }
    // use AuthenticatesUsers;

    // public function __construct()
    // {
    //     $this->middleware('guest')->except('logout');
    // }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Fetch user with eager loaded role
    $user = User::with('role')->where('email', $credentials['email'])->first();

    // Authenticate user
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // Access role from the loaded relationship
        $roleName = $user->role->name;

        if ($roleName == 'admin' || $roleName == 'pegawai' || $roleName == 'kasubagumum') {
            return redirect('/' . $roleName);
        } else {
            return redirect('/default');
        }
    }

    return back()->with('LoginFailed', 'Login Failed');
}


public function logout(Request $request)
{
    Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
}

}

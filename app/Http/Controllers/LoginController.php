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

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $nameCredentials = ['name' => $credentials['email'], 'password' => $credentials['password']];

        if (Auth::attempt($credentials)|| Auth::attempt($nameCredentials)) {
            $user = Auth::user();
            // Retrieve the user based on email and password
            $databaseUser = User::where('password', $credentials['password'])->first();

        // Check if the UUID matches
        if ($databaseUser && $user->uuid !== $databaseUser->uuid) {
            Auth::logout();
            return back()->withErrors([
                'uuid' => 'The provided UUID does not match our records.',
            ]);
        }
            // Authentication passed...
            $request->session()->put('name', $user->name);
            $request->session()->put('email', $user->email);
            $request->session()->put('no_telp', $user->no_telp);
            $request->session()->put('role', $user->role->name);

            // if ($user->role->name == 'admin') {
            //     return redirect('/admin');
            // } elseif ($user->role->name == 'pegawai') {
            //     return redirect('/pegawai');
            // }
            $roleName = $user->role->name;

            if ($roleName == 'admin' || $roleName == 'pegawai' || $roleName == 'kasubagumum') {
                return redirect('/' . $roleName);
            } else {
                return redirect('/default'); // Redirect to a default page for unknown roles
            }


            return redirect('/');
        }

        // Authentication failed...
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

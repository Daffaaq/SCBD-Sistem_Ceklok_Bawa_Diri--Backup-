<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardAdminController extends Controller
{
    public function ViewAdmin(){
        // dd(session());
        return view('Admin/index');
    }
}

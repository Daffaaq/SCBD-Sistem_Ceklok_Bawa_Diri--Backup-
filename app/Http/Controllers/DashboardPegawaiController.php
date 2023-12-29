<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardPegawaiController extends Controller
{
    public function ViewPegawai(){
        // dd(session());
        return view('Pegawai/layouts/index');
    }
}

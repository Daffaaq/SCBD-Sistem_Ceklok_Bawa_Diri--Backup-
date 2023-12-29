<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Absensi_DatangController extends Controller
{
    public function index()
    {
        return view('Pegawai.Absensi.Datang.index');
    }
}

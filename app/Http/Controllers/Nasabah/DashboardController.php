<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $saldo = 100;
        $topup = 50;
        $penarikan = 25;
        // Logic for displaying the dashboard
        return view('pages.nasabah.dashboard', compact('saldo', 'topup', 'penarikan'));
    }

    public function profile()
    {
        // Logic for displaying the profile
        return view('pages.nasabah.profile');
    }
}

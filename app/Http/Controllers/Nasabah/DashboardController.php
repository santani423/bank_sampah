<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Logic for displaying the dashboard
        return view('pages.nasabah.dashboard');
    }

    public function profile()
    {
        // Logic for displaying the profile
        return view('pages.nasabah.profile');
    }
}

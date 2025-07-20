<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\cabang;
use App\Models\Saldo;
use App\Models\UserNasabah;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Can;

class DashboardController extends Controller
{
    public function index()
    {
        
        $topup = 50;
        $penarikan = 25;
        // Logic for displaying the dashboard
        $userNasabah =  UserNasabah::where('user_id', auth()->id())->first();
        $saldo  =  Saldo::where('nasabah_id', $userNasabah->nasabah_id)->first();
        if (!$saldo) {
            $saldo   =  new Saldo();
            $saldo->saldo = 0; 
            $saldo->nasabah_id = $userNasabah->nasabah_id; 
            $saldo->save(); 
        }
        return view('pages.nasabah.dashboard', compact('saldo', 'topup', 'penarikan', 'userNasabah'));
    }

    public function profile()
    {
        // Logic for displaying the profile
        return view('pages.nasabah.profile');
    }
    
}

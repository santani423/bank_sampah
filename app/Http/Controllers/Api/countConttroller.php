<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\cabang;
use App\Models\Nasabah;
use App\Models\User;
use Illuminate\Http\Request;

class countConttroller extends Controller
{
    public function counts(Request $request)
    {
         
        $nasabah = Nasabah::count();
        $petugas = User::where('role', 'petugas')->count();
        $cabang = cabang::count();  
        $data = [
            'count_nasabah' => $nasabah,
            'count_petugas' =>  $petugas,  
            'count_cabang' => $cabang, 
        ];

        return response()->json($data);
    }
}

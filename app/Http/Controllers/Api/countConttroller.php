<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\cabang;
use App\Models\DetailTransaksi;
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
        $totalSampahTerkumpul = DetailTransaksi::sum('berat_kg');
        $data = [
            'count_nasabah' => $nasabah,
            // 'count_nasabah' => 110000000,
            'count_petugas' =>  $petugas,  
            'count_cabang' => $cabang, 
            'total_sampah_terkumpul' => $totalSampahTerkumpul, 
        ];

        return response()->json($data);
    }
}

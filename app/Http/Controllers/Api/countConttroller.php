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
        // Implement your logic to count and return the summary data
        // For example, you might want to return counts of users, transactions, etc.
        $nasabah = Nasabah::count();
        $petugas = User::where('role', 'petugas')->count();
        $cabang = cabang::count(); // Assuming you have a Cabang model for branches
        $data = [
            'count_nasabah' => $nasabah,
            'count_petugas' =>  $petugas, // Example data
            'count_cabang' => $cabang, // Example data
            // Add more counts as needed
        ];

        return response()->json($data);
    }
}

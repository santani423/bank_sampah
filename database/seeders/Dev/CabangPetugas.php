<?php

namespace Database\Seeders\Dev;

use App\Models\cabang;
use App\Models\Petugas;
use App\Models\petugasCabang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CabangPetugas extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cabang = cabang::get();
        $petugas = Petugas::where('username', 'petugas1')->first();
        foreach ($cabang as $key => $cabang) {
            $petugasCabang = new petugasCabang();
            $petugasCabang->cabang_id = $cabang->id;
            $petugasCabang->petugas_id = $petugas->id; // Petugas dengan ID 3
            $petugasCabang->save();
        }
    }
}

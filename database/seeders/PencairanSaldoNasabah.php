<?php

namespace Database\Seeders;

use App\Models\Nasabah;
use App\Models\Saldo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PencairanSaldoNasabah extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $saldo = Saldo::all();

        foreach ($saldo as $s) {
            // Buat pencairan saldo sebesar 50% dari saldo
            $jumlahPencairan = $s->saldo * 0.5;
            $nasabah = Nasabah::with('metodePencairan')->find($s->nasabah_id);

            // Pastikan nasabah dan metode pencairan tersedia
            if ($nasabah && $nasabah->metodePencairan && count($nasabah->metodePencairan) > 0) {
                DB::table('pencairan_saldo')->insert([
                    'nasabah_id' => $s->nasabah_id,
                    'metode_id' => $nasabah->metodePencairan[0]->id,
                    'jumlah_pencairan' => $jumlahPencairan,
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Kurangi saldo nasabah
                $s->decrement('saldo', $jumlahPencairan);
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\DetailTransaksi;
use App\Models\Nasabah;
use App\Models\Sampah;
use App\Models\Transaksi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SetorSampahNasabah extends Seeder
{
    public function run(): void
    {
        $nasabahList = Nasabah::with('saldo')->get();

        foreach ($nasabahList as $nasabah) {

            $jumlahTransaksi = rand(1, 3);

            for ($i = 1; $i <= $jumlahTransaksi; $i++) {

                // Buat transaksi
                $transaksi = Transaksi::create([
                    'kode_transaksi'    => 'TRX-' . $nasabah->id . '-' . now()->format('YmdHis') . '-' . $i,
                    'nasabah_id'        => $nasabah->id,
                    'petugas_id'        => 1,
                    'tanggal_transaksi'=> now()->subDays(rand(0, 30)),
                ]);

                $totalSaldoTambah = 0;

                // Ambil 1–3 jenis sampah
                $sampahList = Sampah::inRandomOrder()
                    ->limit(rand(1, 3))
                    ->get();

                foreach ($sampahList as $sampah) {

                    $beratKg = rand(1, 10); // kg
                    $hargaKg = $sampah->harga_jual;
                    $hargaTotal = $beratKg * $hargaKg;

                    DetailTransaksi::create([
                        'transaksi_id' => $transaksi->id,
                        'sampah_id'    => $sampah->id,
                        'berat_kg'     => $beratKg,
                        // 'harga_per_kg' => $hargaKg,
                        'harga_per_kg' => 10000, // UBAH UNTUK TESTING AJA
                        'harga_total'  => $hargaTotal,
                    ]);

                    $totalSaldoTambah += $hargaTotal;
                }

                // Update saldo nasabah (WAJIB ANGKA)
                // if ($nasabah->saldo) {
                //     $nasabah->saldo()->increment(
                //         'jumlah_saldo',
                //         $totalSaldoTambah,
                //         ['updated_at' => now()]
                //     );
                // }
            }
        }
    }
}

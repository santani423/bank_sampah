<?php

namespace Database\Seeders\Dev;

use App\Models\Lapak;
use App\Models\Sampah;
use App\Models\TransaksiLapak;
use Illuminate\Database\Seeder;

class SetorSampahLapak extends Seeder
{
    public function run(): void
    {
        $lapaks = Lapak::all();

        foreach ($lapaks as $key => $lapak) {

            $jumlahTransaksi = rand(5, 20);

            for ($i = 0; $i < $jumlahTransaksi; $i++) {

                $kodeTransaksi = 'TRXLPK' . str_pad(($key * 100) + $i + 1, 5, '0', STR_PAD_LEFT);

                $transaksi = TransaksiLapak::create([
                    'kode_transaksi'     => $kodeTransaksi,
                    'lapak_id'           => $lapak->id,
                    'petugas_id'         => 3,
                    'tanggal_transaksi' => now()->subDays(rand(1, 30)),
                    'total_transaksi'   => 0, // sementara
                ]);

                $total = 0;

                // Ambil 1–5 jenis sampah secara acak (lebih realistis)
                $sampahs = Sampah::inRandomOrder()->limit(rand(1, 5))->get();

                foreach ($sampahs as $sampah) {
                    $berat = rand(1, 10);
                    $subtotal = $berat * $sampah->harga_lapak;

                    $transaksi->detailTransaksiLapak()->create([
                        'sampah_id'      => $sampah->id,
                        'berat_kg'       => $berat,
                        'harga_per_kg'   => $sampah->harga_lapak,
                        'total_harga'    => $subtotal,
                    ]);

                    $total += $subtotal;
                }

                // Update total transaksi berdasarkan detail
                $transaksi->update([
                    'total_transaksi' => $total
                ]);
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Nasabah;
use App\Models\Saldo;
use App\Models\PencairanSaldo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PencairanSaldoNasabah extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $saldos = Saldo::with('nasabah.metodePencairan.jenisMetodePenarikan')->get();

        foreach ($saldos as $saldo) {

            // Ambil nasabah
            $nasabah = $saldo->nasabah;

            if (
                !$nasabah ||
                !$nasabah->metodePencairan ||
                $nasabah->metodePencairan->isEmpty()
            ) {
                continue;
            }

            // Ambil metode pencairan pertama
            $metode = $nasabah->metodePencairan->first();
            $jenis  = $metode->jenisMetodePenarikan;

            if (!$jenis) {
                continue;
            }

            // =========================
            // Hitung jumlah pencairan
            // =========================
            $jumlahPencairan = round($saldo->saldo * 0.5, 2);

            if ($jumlahPencairan <= 0) {
                continue;
            }

            // =========================
            // Hitung Fee (SESUAI SISTEM)
            // =========================
            $baseFee    = (float) ($jenis->base_fee ?? 0);
            $ppnPercent = (float) ($jenis->ppn_percent ?? 0);
            $ppnValue   = $baseFee * $ppnPercent / 100;
            $feeNet     = $baseFee + $ppnValue;

            $totalPencairan = $jumlahPencairan;

            if ($jenis->fee_bearer !== 'COMPANY') {
                $totalPencairan -= $feeNet;
            }

            // Hindari data tidak valid
            if ($totalPencairan <= 0) {
                continue;
            }

            // =========================
            // Insert ke pencairan_saldo
            // =========================
            PencairanSaldo::create([
                'nasabah_id'        => $nasabah->id,
                'metode_id'         => $metode->id,
                'jumlah_pencairan'  => $jumlahPencairan,
                'total_pencairan'   => $totalPencairan,
                'ppn_percent'       => $ppnPercent,
                'fee_gross'         => $baseFee,
                'fee_net'           => $feeNet,
                'fee_bearer'        => $jenis->fee_bearer === 'COMPANY'
                                        ? 'COMPANY'
                                        : 'NASABAH',
                'status'            => 'pending',
                'tanggal_pengajuan' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            /**
             * ❗ PENTING
             * Seeder TIDAK mengurangi saldo.
             * Saldo hanya boleh dikurangi saat:
             * - status = disetujui
             * - proses admin / approval
             */
        }
    }
}

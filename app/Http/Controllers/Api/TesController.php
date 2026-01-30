<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PencairanLapak;
use App\Models\SaldoUtama;
use App\Models\Tess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class TesController extends Controller
{
    public function disbursementSend(Request $request)
    {
        try {
            // 1️⃣ Simpan payload mentah (audit webhook)
            Tess::create([
                'name' => 'disbursementSend',
                'description' => json_encode($request->all()),
            ]);

            // 2️⃣ Ambil data sesuai payload Xendit
            $externalId = data_get($request->all(), 'external_id');
            $status     = data_get($request->all(), 'status');

            if (empty($externalId) || empty($status)) {
                return response()->json([
                    'message' => 'Invalid Xendit payload'
                ], 200); // tetap 200 agar Xendit tidak retry
            }

            // 3️⃣ Transaction DB (WAJIB untuk transaksi uang)
            DB::transaction(function () use ($externalId, $status) {

                $pencairanLapak = PencairanLapak::where('kode_pencairan', $externalId)
                    ->lockForUpdate()
                    ->first();

                if (!$pencairanLapak) {
                    throw new \Exception('Pencairan tidak ditemukan');
                }

                // ❗ Idempotent (hindari double webhook)
                if ($pencairanLapak->status === 'COMPLETED') {
                    return;
                }

                // 4️⃣ Mapping status Xendit → internal
                switch ($status) {
                    case 'COMPLETED':
                        $pencairanLapak->status = 'COMPLETED';

                        // Potong saldo admin jika sumber dana admin
                        if ($pencairanLapak->sumber_dana === 'saldo_admin') {

                            $saldoUtama = SaldoUtama::lockForUpdate()->first();

                            if (!$saldoUtama || $saldoUtama->saldo < $pencairanLapak->jumlah_pencairan) {
                                throw new \Exception('Saldo admin tidak mencukupi');
                            }

                            $saldoUtama->saldo -= $pencairanLapak->jumlah_pencairan;
                            $saldoUtama->save();
                        }
                        break;

                    case 'FAILED':
                        $pencairanLapak->status = 'FAILED';
                        break;
                }

                $pencairanLapak->save();
            });

            // 5️⃣ Xendit SUCCESS RESPONSE
            return response()->json([
                'message' => 'Webhook processed successfully'
            ], 200);
        } catch (Throwable $e) {

            Log::error('Xendit Disbursement Webhook Error', [
                'error' => $e->getMessage(),
                'payload' => $request->all(),
            ]);

            // ⚠️ WAJIB 200 agar Xendit tidak retry
            return response()->json([
                'message' => 'Webhook received'
            ], 200);
        }
    }
}

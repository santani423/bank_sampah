<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PencairanLapak;
use App\Models\Tess;
use Illuminate\Http\Request;
use Throwable;

class TesController extends Controller
{


    public function disbursementSend(Request $request)
    {
        try {
            // Simpan log request (opsional buat debug)
            $data = new Tess();
            $data->name = 'disbursementSend';
            $data->description = json_encode($request->all());
            $data->save();

            // Ambil data transaksi dari payload
            $externalId = data_get($request, 'transaction.external_id');
            $status     = data_get($request, 'transaction.status');

            if (!$externalId || !$status) {
                return response()->json([
                    'message' => 'Invalid payload structure',
                ], 422);
            }

            // Update status pencairan
            $transaksi = PencairanLapak::where('kode_pencairan', $externalId)->first();

            if ($transaksi) {
                $transaksi->status = $status;
                $transaksi->save();
            }

            return response()->json([
                'message' => 'Disbursement processed successfully',
                'data' => $request->all()
            ], 200);
        } catch (Throwable $e) {

            // Log error biar gampang tracing
            Log::error('DisbursementSend Error', [
                'error' => $e->getMessage(),
                'payload' => $request->all()
            ]);

            return response()->json([
                'message' => 'Failed to process disbursement',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

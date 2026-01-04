<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PencairanSaldo;
use Illuminate\Http\Request;

class PencairanSaldoController extends Controller
{
    public function index()
    {
        try {
            $pencairanSaldo = PencairanSaldo::with(['nasabah', 'metode'])
                ->where('status', 'pending')
                ->orderBy('tanggal_pengajuan', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'message' => 'Data pencairan saldo berhasil diambil.',
                'data'    => $pencairanSaldo->items(),
                'pagination' => [
                    'current_page' => $pencairanSaldo->currentPage(),
                    'last_page'    => $pencairanSaldo->lastPage(),
                    'per_page'     => $pencairanSaldo->perPage(),
                    'total'        => $pencairanSaldo->total(),
                    'from'        => $pencairanSaldo->firstItem(),
                    'to'        => $pencairanSaldo->lastItem(),

                ],
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data pencairan saldo.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}

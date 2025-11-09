<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TransaksiNasabahBadan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NasabahBadanTransaksiController extends Controller
{
    /**
     * Get transaction history for a specific nasabah badan with pagination and date range filter
     */
    public function getTransactionHistory(Request $request, $nasabahBadanId)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            // Build query
            $query = TransaksiNasabahBadan::with(['detailTransaksi.sampah', 'petugas'])
                ->where('nasabah_badan_id', $nasabahBadanId);

            // Apply date range filter if provided
            if ($startDate) {
                $query->whereDate('tanggal_transaksi', '>=', Carbon::parse($startDate));
            }

            if ($endDate) {
                $query->whereDate('tanggal_transaksi', '<=', Carbon::parse($endDate));
            }

            // Order by latest transaction first
            $query->orderByDesc('tanggal_transaksi')->orderByDesc('id');

            // Paginate results
            $transactions = $query->paginate($perPage);

            // Transform the data for better response structure
            $transformedData = $transactions->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'kode_transaksi' => $transaction->kode_transaksi,
                    'tanggal_transaksi' => $transaction->tanggal_transaksi->format('Y-m-d'),
                    'tanggal_transaksi_formatted' => $transaction->tanggal_transaksi->format('d/m/Y'),
                    'total_transaksi' => (float) $transaction->total_transaksi,
                    'total_transaksi_formatted' => 'Rp ' . number_format($transaction->total_transaksi, 0, ',', '.'),
                    'status' => $transaction->status,
                    'keterangan' => $transaction->keterangan,
                    'petugas' => [
                        'id' => $transaction->petugas->id ?? null,
                        'nama' => $transaction->petugas->nama ?? '-',
                    ],
                    'detail_sampah' => $transaction->detailTransaksi->map(function ($detail) {
                        return [
                            'id' => $detail->id,
                            'sampah_nama' => $detail->sampah->nama_sampah ?? '-',
                            'sampah_jenis' => $detail->sampah->jenis_sampah ?? '-',
                            'berat_kg' => (float) $detail->berat_kg,
                            'berat_kg_formatted' => number_format($detail->berat_kg, 2, ',', '.') . ' kg',
                            'harga_per_kg' => (float) $detail->harga_per_kg,
                            'harga_per_kg_formatted' => 'Rp ' . number_format($detail->harga_per_kg, 0, ',', '.'),
                            'harga_total' => (float) $detail->harga_total,
                            'harga_total_formatted' => 'Rp ' . number_format($detail->harga_total, 0, ',', '.'),
                        ];
                    }),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Data transaksi berhasil dimuat',
                'data' => $transformedData,
                'pagination' => [
                    'current_page' => $transactions->currentPage(),
                    'last_page' => $transactions->lastPage(),
                    'per_page' => $transactions->perPage(),
                    'total' => $transactions->total(),
                    'from' => $transactions->firstItem(),
                    'to' => $transactions->lastItem(),
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data transaksi',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

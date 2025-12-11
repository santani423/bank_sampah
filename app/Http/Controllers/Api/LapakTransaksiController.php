<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SaldoUtama;
use App\Models\TransaksiLapak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Services\WhatsAppService; // ✅ Tambahkan ini

class LapakTransaksiController extends Controller
{
    // GET /api/lapak/{id}/transaksi
    public function index(Request $request, $id)
    {
        $query = DB::table('transaksi_lapak')
            ->where('lapak_id', $id);

        // Search by kode transaksi
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('kode_transaksi', 'like', "%$search%");
        }

        // Search by tanggal transaksi
        if ($request->filled('tanggal')) {
            $tanggal = $request->input('tanggal');
            $query->whereDate('tanggal_transaksi', $tanggal);
        }

        $query->orderByDesc('created_at');

        // Pagination
        $limit = $request->input('limit', 10);
        $page = $request->input('page', 1);
        $total = $query->count();
        $lastPage = ceil($total / $limit);
        $transaksi = $query->offset(($page - 1) * $limit)->limit($limit)->get();

        // Map detail transaksi
        $transaksi = $transaksi->map(function ($trx) {
            $details = DB::table('detail_transaksi_lapak')
                ->where('transaksi_lapak_id', $trx->id)
                ->get();
            $trx->detail_transaksi = $details;
            $trx->status = $trx->status ?? 'pending';
            $trx->approval = $trx->approval_status ?? 'pending';
            return $trx;
        });

        return response()->json([
            'data' => $transaksi,
            'total' => $total,
            'current_page' => (int)$page,
            'last_page' => (int)$lastPage
        ]);
    }

    public function readyToShips(Request $request, $id)
    {
        $query = DB::table('transaksi_lapak')
            ->where('lapak_id', $id);

        // Search by kode transaksi
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('kode_transaksi', 'like', "%$search%");
        }

        // Search by tanggal transaksi
        if ($request->filled('tanggal')) {
            $tanggal = $request->input('tanggal');
            $query->whereDate('tanggal_transaksi', $tanggal);
        }

        $query->orderByDesc('created_at');

        // Pagination
        $limit = $request->input('limit', 10);
        $page = $request->input('page', 1);
        $total = $query->count();
        $lastPage = ceil($total / $limit);
        $transaksi = $query->offset(($page - 1) * $limit)->limit($limit)->get();

        // Map detail transaksi
        $transaksi = $transaksi->map(function ($trx) {
            $details = DB::table('detail_transaksi_lapak')
                ->where('transaksi_lapak_id', $trx->id)
                ->get();
            $trx->detail_transaksi = $details;
            $trx->status = $trx->status ?? 'pending';
            $trx->approval = $trx->approval_status ?? 'pending';
            return $trx;
        });

        return response()->json([
            'data' => $transaksi,
            'total' => $total,
            'current_page' => (int)$page,
            'last_page' => (int)$lastPage
        ]);
    }

    // POST /api/transaksi-lapak/{id}/ambil-saldo
    public function ambilSaldo(Request $request, $id)
    {
        // Validasi dan proses ambil saldo

        $trx = TransaksiLapak::with('lapak')->where('id', $id)->first();
        $lapak = $trx ? $trx->lapak : null;
        if (!$trx) {
            return response()->json(['status' => false, 'message' => 'Transaksi tidak ditemukan'], 404);
        }
        $saldoUtama = SaldoUtama::first();
        if ($saldoUtama->saldo < $trx->total_transaksi) {
            return response()->json(['status' => false, 'message' => 'Saldo utama tidak mencukupi'], 400);
        }

        $saldoUtama->saldo -= $trx->total_transaksi;
        $saldoUtama->save();

        // Generate external_id unik
        $externalId = 'disb-dana-' . time() . '-' . Str::random(5);
        // Buat payload sesuai format disbursement Xendit
        $payload = [
            'external_id' => $externalId,
            'amount' => (int) $trx->total_transaksi,
            'bank_code' => 'DANA', // ✅ HARUS: gunakan DANA sebagai bank_code
            'account_holder_name' => $lapak->nama_lapak,
            'account_number' =>  $lapak->no_telepon, // ✅ HARUS: ini nomor telepon penerima
            'description' =>  'Disbursement ke DANA'
        ];
        $response = Http::withBasicAuth(config('xendit.api_key'), '')
            ->post('https://api.xendit.co/disbursements', $payload);
        // // Contoh logika: update status dan approval
        // DB::table('transaksi_lapak')->where('id', $id)->update([
        //     'approval' => 'approved', 
        // ]); 
        // return response()->json(['status' => true, 'message' => 'Saldo berhasil diambil']);


        // // TODO: Tambahkan logika pengurangan saldo lapak jika diperlukan

        return response()->json(['status' => true, 'message' => 'Saldo berhasil diambil']);
    }
}

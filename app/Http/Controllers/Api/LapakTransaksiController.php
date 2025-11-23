<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}

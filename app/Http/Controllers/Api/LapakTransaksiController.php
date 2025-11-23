<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LapakTransaksiController extends Controller
{
    // GET /api/lapak/{id}/transaksi
    public function index($id)
    {
        $transaksi = DB::table('transaksi_lapak')
            ->where('lapak_id', $id)
            ->orderByDesc('created_at')
            ->get();
        return response()->json($transaksi);
    }
}

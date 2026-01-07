<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nasabah;
use App\Models\Petugas;
use App\Models\Sampah;
use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use App\Models\Saldo;
use App\Models\Artikel;
use App\Models\Feedback;
use App\Models\PencairanSaldo;
use App\Models\PengirimanPengepul;
use App\Models\DetailPengiriman;
use App\Models\PencairanLapak;
use App\Models\PengirimanLapak;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalNasabah = Nasabah::count();
        $totalPetugas = Petugas::count();
        $totalSampahTerkumpul = DetailTransaksi::sum('berat_kg');
        $totalTransaksiSetoran = Transaksi::count();
        $totalSaldoNasabah = Saldo::sum('saldo');
        $totalPermintaanPencairan = PencairanSaldo::where('status', 'pending')->count();
        $totalFeedbackMasuk = Feedback::count();
        $totalArtikel = Artikel::count();

        // Tambahan baru
        $totalStokSampah = Sampah::with(['detailTransaksi', 'detailPengiriman'])
            ->get()
            ->sum(function ($sampah) {
                $totalMasuk = $sampah->detailTransaksi->sum('berat_kg');
                $totalKeluar = $sampah->detailPengiriman->sum('berat_kg');
                return $totalMasuk - $totalKeluar;
            });

        $totalSampahTerkirim = DetailPengiriman::sum('berat_kg');

        $totalKeuntungan = DetailPengiriman::get()->sum(function ($detail) {
            return ($detail->harga_per_kg - $detail->harga_beli) * $detail->berat_kg;
        });


        $nasabahTerbaik = Nasabah::withCount(['transaksi as total_setoran' => function ($query) {
            $query->where('tanggal_transaksi', '>=', now()->subMonth());
        }])->orderBy('total_setoran', 'desc')->take(10)->get();

        return view('pages.admin.dashboard', compact(
            'totalNasabah',
            'totalPetugas',
            'totalSampahTerkumpul',
            'totalTransaksiSetoran',
            'totalSaldoNasabah',
            'totalPermintaanPencairan',
            'totalFeedbackMasuk',
            'totalArtikel',
            'totalStokSampah',
            'totalSampahTerkirim',
            'totalKeuntungan',
            'nasabahTerbaik'
        ));
    }

    public function faceUser()
    {
        return view('pages.admin.faceUser');
    }


    public function invoicPencairanLapak($kode)
    {

        $pencairan = PencairanLapak::with([
            'pengirimanLapak.detailPengirimanLapaks.transaksiLapak.detailTransaksiLapak',
            'pengirimanLapak.gudang.cabang',
            'pengirimanLapak.lapak',
            'pengirimanLapak.petugas'
        ])->where('kode_pencairan', $kode)->firstOrFail();

        // Akses langsung
        $pengiriman = $pencairan->pengirimanLapak;
        // dd($pencairan);

        // Ambil cabang dari relasi gudang
        $cabang = $pengiriman && $pengiriman->gudang ? $pengiriman->gudang->cabang : null;
        return view('pages.admin.lapak.invoicPencairanLapak', compact('pengiriman', 'cabang', 'kode','pencairan'));
    }
}

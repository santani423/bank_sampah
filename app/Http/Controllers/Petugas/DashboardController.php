<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nasabah;
use App\Models\DetailTransaksi;
use App\Models\PengirimanLapak;
use App\Models\saldoPetugas;
use App\Models\Transaksi;
use App\Models\TransaksiLapak;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Nasabah

        $auth = auth()->user();


        // Hitung total nasabah langsung di DB
        $totalNasabah = Nasabah::join('user_nasabahs', 'nasabah.id', '=', 'user_nasabahs.nasabah_id')
            ->join('cabang_users', 'user_nasabahs.id', '=', 'cabang_users.user_nasabah_id')
            ->join('cabangs', 'cabang_users.cabang_id', '=', 'cabangs.id')
            ->join('petugas_cabangs', 'cabangs.id', '=', 'petugas_cabangs.cabang_id')
            ->join('petugas', 'petugas_cabangs.petugas_id', '=', 'petugas.id')
            ->where('petugas.email', auth()->user()->email)
            ->distinct('nasabah.id') // pastikan hanya menghitung nasabah unik
            ->count('nasabah.id'); // langsung dihitung di DB


        $saldoPetugas =  SaldoPetugas::join('petugas', 'saldo_petugas.petugas_id', '=', 'petugas.id')
            ->where('petugas.email', auth()->user()->email)
            ->first();

        // Total Transaksi Hari Ini
        $totalTransaksiHariIni = Transaksi::whereDate('tanggal_transaksi', Carbon::today())->count();

        // Total Sampah (Kg) Hari Ini
        $totalSampahHariIni = DetailTransaksi::whereHas('transaksi', function ($query) {
            $query->whereDate('tanggal_transaksi', Carbon::today());
        })->sum('berat_kg');

        // Total Omzet Sampah Hari Ini
        $totalOmzetHariIni = DetailTransaksi::whereHas('transaksi', function ($query) {
            $query->whereDate('tanggal_transaksi', Carbon::today());
        })->get()->sum(function ($detailTransaksi) {
            return $detailTransaksi->berat_kg * $detailTransaksi->harga_per_kg;
        });

        $nasabahTerbaik = Nasabah::with(['transaksi' => function ($query) {
            $query->withSum('detailTransaksi as total_sampah', 'berat_kg')
                ->whereBetween('tanggal_transaksi', [
                    Carbon::now()->subMonth()->startOfMonth(),
                    Carbon::now()->subMonth()->endOfMonth()
                ]);
        }])
            ->whereHas('transaksi.detailTransaksi', function ($query) {
                $query->where('berat_kg', '>', 0);
            })
            ->withCount(['transaksi as total_setoran' => function ($query) {
                $query->whereBetween('tanggal_transaksi', [
                    Carbon::now()->subMonth()->startOfMonth(),
                    Carbon::now()->subMonth()->endOfMonth()
                ]);
            }])
            ->get()
            ->sortByDesc(function ($nasabah) {
                return $nasabah->transaksi->sum('detailTransaksi.berat_kg');
            })->take(10);


        // Kirim data ke view
        return view('pages.petugas.dashboard', compact(
            'totalNasabah',
            'totalTransaksiHariIni',
            'totalSampahHariIni',
            'totalOmzetHariIni',
            'nasabahTerbaik',
            'saldoPetugas'
        ));
    }

    public function invoicKirimSampahLapoak($kode = null)
    {
        $invoice = PengirimanLapak::with(['detailPengirimanLapaks.transaksiLapak.detailTransaksiLapak.sampah', 'gudang', 'petugas'])
            ->where('kode_pengiriman', $kode)
            ->first();



        // dd($invoice);
        return view('pages.petugas.invoic.kirim-sampah-lapak', compact('invoice', 'kode'));
    }



    public function invoicPencairanLapak($kode = null)
    {
        $invoice = PengirimanLapak::with(['detailPengirimanLapaks.transaksiLapak.detailTransaksiLapak.sampah', 'gudang', 'petugas', 'lapak'])
            ->where('kode_pengiriman', $kode)
            ->first();
        // dd($invoice);
        // return view('pages.petugas.invoic.pencairan-lapak', compact('invoice','kode'));
    }

    public function invoicKirimSampahLapak($kode = null)
    {
        $invoice = PengirimanLapak::with(['detailPengirimanLapaks.transaksiLapak.detailTransaksiLapak.sampah', 'gudang', 'petugas'])
            ->where('kode_pengiriman', $kode)
            ->first();



        // dd($invoice);
        return view('pages.petugas.invoic.kirim-sampah-lapak', compact('invoice', 'kode'));
    }
}

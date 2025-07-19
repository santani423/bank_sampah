<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\PencairanSaldo;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\UserNasabah;
use Illuminate\Http\Request;

class NasabahTransaksiController extends Controller
{
    public function index()
    {
        $saldo = 100;
        // Logic for displaying the transactions
        return view('pages.nasabah.transaksi.index', compact('saldo'));
    }

    public function create()
    {
        // Logic for showing the form to create a new transaction
        return view('pages.nasabah.transaksi.create');
    }

    public function setoran()
    {
        // Ambil riwayat setoran (transaksi)
        $userNasabah = UserNasabah::where('user_id', auth()->id())->first();
        $id = $userNasabah->nasabah_id;
        $riwayatSetoran = Transaksi::with(['detailTransaksi.sampah'])
            ->where('nasabah_id', $id)
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();
        // Logic for showing the setoran transaction form
        return view('pages.nasabah.transaksi.setoran', compact('riwayatSetoran'));
    }

    public function penarikan()
    {
        // Ambil riwayat penarikan (transaksi)
        $userNasabah = UserNasabah::where('user_id', auth()->id())->first();
        $id = $userNasabah->nasabah_id;
        $riwayatPenarikan = PencairanSaldo::with('metodePencairan')
            ->where('nasabah_id', $id)
            ->orderBy('tanggal_pengajuan', 'desc')
            ->get();

        // Logic for showing the penarikan transaction form
        return view('pages.nasabah.transaksi.penarikan', compact('riwayatPenarikan'));
    }
}

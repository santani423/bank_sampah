<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\MetodePencairan;
use App\Models\PencairanSaldo;
use App\Models\Saldo;
use App\Models\setting;
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
        $riwayatPenarikan = PencairanSaldo::with('metode')
            ->where('nasabah_id', $id)
            ->orderBy('tanggal_pengajuan', 'desc')
            ->get();
        $metodePenarikan = MetodePencairan::where('nasabah_id', $userNasabah->nasabah_id)
            ->with('jenisMetodePenarikan')
            ->get();
        $pencairanSaldo = PencairanSaldo::where('nasabah_id', $userNasabah->nasabah_id)
            ->with('metode')
            ->get();
        // dd($riwayatPenarikan);
        // Logic for showing the penarikan transaction form
        $setting = setting::first();
        $minPenarikan = $setting->min_penarikan;
        return view('pages.nasabah.transaksi.penarikan', compact('riwayatPenarikan', 'metodePenarikan', 'pencairanSaldo', 'minPenarikan'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'jumlah_pencairan' => 'required|numeric|min:10000',
            'metode_pencairan_id' => 'required', // sesuaikan dengan nama tabelmu
        ]);

        // Ambil data nasabah berdasarkan user login
        $userNasabah = UserNasabah::where('user_id', auth()->id())->firstOrFail();
        $nasabahId = $userNasabah->nasabah_id;

        // Ambil data saldo
        $saldo = Saldo::where('nasabah_id', $nasabahId)->first();
        // dd($saldo);
        // Ambil biaya admin dari env atau config
        $adminPey = env('ADMIN_PEY', 0); // atau config('admin.pey', 0);

        // Cek apakah saldo mencukupi
        if (($saldo->saldo - $adminPey) < $request->jumlah_pencairan) {
            return redirect()->route('nasabah.transaksi.penarikan')
                ->with('error', 'Saldo tidak mencukupi untuk melakukan penarikan.');
        }

        // Simpan pencairan
        PencairanSaldo::create([
            'nasabah_id' => $nasabahId,
            'jumlah_pencairan' => $request->jumlah_pencairan,
            'metode_id' => $request->metode_pencairan_id,
            'status' => 'pending', // tambahkan jika ada kolom status
        ]);

        return redirect()->route('nasabah.transaksi.penarikan')
            ->with('success', 'Pengajuan penarikan berhasil dikirim.');
    }
}

<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\MetodePencairan;
use App\Models\PencairanSaldo;
use App\Models\Saldo;
use App\Models\Setting;
use App\Models\Transaksi;
use App\Models\UserNasabah;
use Illuminate\Http\Request;
use App\Services\WhatsAppService; // ✅ Tambahkan ini

class NasabahTransaksiController extends Controller
{
    protected $whatsappService;

    // ✅ Injeksi service WhatsApp melalui konstruktor
    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function index()
    {
        $saldo = 100;
        return view('pages.nasabah.transaksi.index', compact('saldo'));
    }

    public function create()
    {
        return view('pages.nasabah.transaksi.create');
    }

    public function setoran()
    {
        $userNasabah = UserNasabah::where('user_id', auth()->id())->first();
        $id = $userNasabah->nasabah_id;
        $riwayatSetoran = Transaksi::with(['detailTransaksi.sampah'])
            ->where('nasabah_id', $id)
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        return view('pages.nasabah.transaksi.setoran', compact('riwayatSetoran'));
    }

    public function penarikan()
    {
        $userNasabah = UserNasabah::where('user_id', auth()->id())->first();
        $id = $userNasabah->nasabah_id;

        $riwayatPenarikan = PencairanSaldo::with('metode')
            ->where('nasabah_id', $id)
            ->orderBy('tanggal_pengajuan', 'desc')
            ->get();

        $metodePenarikan = MetodePencairan::where('nasabah_id', $id)
            ->with('jenisMetodePenarikan')
            ->get();

        $pencairanSaldo = PencairanSaldo::where('nasabah_id', $id)
            ->with('metode')
            ->get();

        $setting = Setting::first();
        $minPenarikan = $setting->min_penarikan;

        return view('pages.nasabah.transaksi.penarikan', compact(
            'riwayatPenarikan',
            'metodePenarikan',
            'pencairanSaldo',
            'minPenarikan'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jumlah_pencairan' => 'required|numeric|min:10000',
            'metode_pencairan_id' => 'required',
        ]);

        $userNasabah = UserNasabah::with('nasabah')
            ->where('user_id', auth()->id())
            ->firstOrFail();



        $nasabahId = $userNasabah->nasabah_id;

        $saldo = Saldo::where('nasabah_id', $nasabahId)->first();
        $adminPey = env('ADMIN_PEY', 0);

        if (($saldo->saldo - $adminPey) < $request->jumlah_pencairan) {
            return redirect()->route('nasabah.transaksi.penarikan')
                ->with('error', 'Saldo tidak mencukupi untuk melakukan penarikan.');
        }

        // Simpan pencairan
        $pencairan = PencairanSaldo::create([
            'nasabah_id' => $nasabahId,
            'jumlah_pencairan' => $request->jumlah_pencairan,
            'metode_id' => $request->metode_pencairan_id,
            'status' => 'pending',
        ]);
        $nasabah = $userNasabah->nasabah;




        if ($nasabah) {
            $setting = Setting::first();
            $pesan = "*Pemberitahuan Penarikan Saldo*\n\n"
                . "Halo *{$nasabah->nama_lengkap}*,\n"
                . "Permintaan pencairan saldo Anda sebesar *Rp "
                . number_format($request->jumlah_pencairan, 0, ',', '.') . "* telah diterima dan sedang diproses.\n\n"
                . "_Status: Pending_\n"
                . "Terima kasih telah menggunakan layanan kami *{$setting->nama}*.";
            $pesanAdmin = "*Pemberitahuan Admin*\n\n"
                . "Nasabah *{$nasabah->nama_lengkap}* (No HP: {$nasabah->no_hp}) "
                . "telah melakukan permintaan pencairan saldo sebesar *Rp "
                . number_format($request->jumlah_pencairan, 0, ',', '.') . "*.\n\n"
                . "_Status: Pending_\n"
                . "Segera lakukan proses pencairan jika sudah sesuai.";

            // 🔥 Panggil service WhatsApp
            $result = $this->whatsappService->sendMessage($setting->no_notifikasi, $pesanAdmin);
            $result2 = $this->whatsappService->sendMessage($nasabah->no_hp, $pesan);
        }


        return redirect()->route('nasabah.transaksi.penarikan')
            ->with('success', 'Pengajuan penarikan berhasil dikirim dan notifikasi WhatsApp telah dikirim.');
    }
}

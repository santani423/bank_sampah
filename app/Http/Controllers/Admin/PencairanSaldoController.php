<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PencairanSaldo;
use App\Models\Saldo;
use App\Models\setting;
use App\Models\User;
use App\Models\UserNasabah;
use Illuminate\Support\Facades\Log;
use Xendit\Invoice\InvoiceApi;
use Xendit\Configuration;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Services\WhatsAppService; // ✅ Tambahkan ini

class PencairanSaldoController extends Controller
{
    protected InvoiceApi $invoiceApi;
    protected $whatsappService;


    public function __construct(WhatsAppService $whatsappService)
    {
        $apiKey = config('xendit.api_key');

        if (empty($apiKey)) {
            Log::error('Xendit API Key is missing in configuration. Please ensure XENDIT_API_KEY is set in your .env file and configured in config/xendit.php.');
            abort(500, 'Xendit API Key tidak tersedia. Harap periksa konfigurasi aplikasi Anda.');
        }

        // Set the Xendit API key globally for the SDK
        Configuration::setXenditKey($apiKey);

        // Initialize the InvoiceApi (from previous context)
        $this->invoiceApi = new InvoiceApi();
        $this->whatsappService = $whatsappService;
    }
    public function index()
    {
        $pencairanSaldo = PencairanSaldo::with(['nasabah', 'metode'])
            ->where('status', 'pending')
            ->orderBy('tanggal_pengajuan', 'desc')
            ->paginate(10);

        return view('pages.admin.pencairan_saldo.index', compact('pencairanSaldo'));
    }

    /**
     * Proses persetujuan permintaan pencairan saldo.
     */
    public function setujui(Request $request, $id)
    {
        $request->validate([
            'jumlah_pencairan' => 'required|numeric|min:0',
        ]);

        $pencairan = PencairanSaldo::findOrFail($id);

        // Pastikan statusnya masih pending
        if ($pencairan->status !== 'pending') {
            return redirect()->back()->withErrors(['msg' => 'Permintaan sudah diproses sebelumnya.']);
        }

        // Cek saldo nasabah
        $saldo = Saldo::where('nasabah_id', $pencairan->nasabah_id)->first();

        if (!$saldo || $saldo->saldo < $pencairan->jumlah_pencairan) {
            return redirect()->back()->withErrors(['msg' => 'Saldo tidak mencukupi untuk pencairan.']);
        }

        // Proses pengurangan saldo
        $saldo->saldo -= $pencairan->jumlah_pencairan;
        $saldo->save();

        // Update status pencairan saldo
        $pencairan->status = 'disetujui';
        $pencairan->tanggal_proses = now();
        $pencairan->updated_at = now();
        $pencairan->save();

        $nasabahUser  = UserNasabah::with('nasabah')->where('nasabah_id', $pencairan->nasabah_id)->first();
        $user = User::where('id', $nasabahUser->user_id)->first();

        // Generate external_id unik
        $externalId = 'disb-dana-' . time() . '-' . Str::random(5);
        // Buat payload sesuai format disbursement Xendit
        $payload = [
            'external_id' => $externalId,
            'amount' => (int) $pencairan->jumlah_pencairan,
            'bank_code' => 'DANA', // ✅ HARUS: gunakan DANA sebagai bank_code
            'account_holder_name' => $user->name,
            'account_number' => "085778674418", // ✅ HARUS: ini nomor telepon penerima
            'description' =>  'Disbursement ke DANA'
        ];
        $response = Http::withBasicAuth(config('xendit.api_key'), '')
            ->post('https://api.xendit.co/disbursements', $payload);

        if ($response->successful()) {
            Log::info('DANA Disbursement berhasil', [
                'external_id' => $externalId,
                'response' => $response->json()
            ]);

            // return response()->json([
            //     'message' => 'Disbursement DANA berhasil dikirim',
            //     'data' => $response->json()
            // ]);
        } else {
            Log::error('Gagal kirim DANA Disbursement', [
                'payload' => $payload,
                'response' => $response->json()
            ]);

            // return response()->json([
            //     'message' => 'Gagal kirim DANA Disbursement',
            //     'error' => $response->json()
            // ], $response->status());
        }

        $nasabah = $nasabahUser->nasabah;




        if ($nasabah) {
            $setting = Setting::first();
            $pesan = "*Pemberitahuan Pencairan Saldo*\n\n"
                . "Halo *{$nasabah->nama_lengkap}*, 👋\n"
                . "Permintaan pencairan saldo Anda sebesar *Rp " . number_format($pencairan->jumlah_pencairan, 0, ',', '.') . "* telah *DISETUJUI* dan sedang dalam proses pengiriman ke akun DANA Anda.\n\n"
                . "📅 *Tanggal Proses:* " . now()->format('d-m-Y H:i') . "\n"
                . "💸 *Status:* Disetujui ✅\n\n"
                . "Terima kasih telah menggunakan layanan *{$setting->nama}*. 🌱";

            // 🔥 Kirim pesan via WhatsApp Service
            $result = $this->whatsappService->sendMessage($nasabah->no_hp, $pesan);
        }


        return back()->with('success', 'Permintaan pencairan saldo telah disetujui.');
    }

    /**
     * Proses penolakan permintaan pencairan saldo.
     */
    public function tolak(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'required|string|max:255',
        ]);

        // Ambil data pencairan saldo berdasarkan ID
        $pencairan = PencairanSaldo::findOrFail($id);

        // Pastikan status masih 'pending'
        if ($pencairan->status !== 'pending') {
            return redirect()->back()->withErrors(['msg' => 'Permintaan sudah diproses sebelumnya.']);
        }

        // Update status pencairan menjadi 'ditolak'
        $pencairan->status = 'ditolak';
        $pencairan->keterangan = $request->keterangan;
        $pencairan->tanggal_proses = now();
        $pencairan->save();

        // Ambil data nasabah berdasarkan user_id dari pencairan
        $userNasabah = UserNasabah::with('nasabah')->where('nasabah_id', $pencairan->nasabah_id)->first();
        $nasabah = $userNasabah ? $userNasabah->nasabah : null;

        

        if ($nasabah) {
            $setting = Setting::first();
            $pesan = "*Pemberitahuan Penolakan Pencairan Saldo*\n\n"
                . "Halo *{$nasabah->nama_lengkap}*, 👋\n"
                . "Permintaan pencairan saldo Anda sebesar *Rp " . number_format($pencairan->jumlah_pencairan, 0, ',', '.') . "* telah *DITOLAK*.\n\n"
                . "📅 *Tanggal Proses:* " . now()->format('d-m-Y H:i') . "\n"
                . "📝 *Alasan Penolakan:* {$request->keterangan}\n\n"
                . "Silakan hubungi admin untuk informasi lebih lanjut.\n\n"
                . "Terima kasih telah menggunakan layanan *{$setting->nama}*. 🌱";

            // Kirim pesan via WhatsApp Service
            $this->whatsappService->sendMessage($nasabah->no_hp, $pesan);
        }

        return back()->with('error', 'Pengajuan pencairan saldo telah ditolak.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PencairanSaldo;
use App\Models\Saldo;
use App\Models\User;
use App\Models\UserNasabah;
use Illuminate\Support\Facades\Log;
use Xendit\Invoice\InvoiceApi;
use Xendit\Configuration;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PencairanSaldoController extends Controller
{
    protected InvoiceApi $invoiceApi;


    public function __construct()
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
        
        $nasabahUser  = UserNasabah::where('nasabah_id',$pencairan->nasabah_id)->first();
        $user = User::where('id',$nasabahUser->user_id)->first();
         
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

        // Generate external_id unik
        $externalId = 'disb-dana-' . time() . '-' . Str::random(5);

        // Buat payload sesuai format disbursement Xendit
        $payload = [
            'external_id' => $externalId,
            'amount' => (int) $request->amount,
            'bank_code' => 'DANA', // ✅ HARUS: gunakan DANA sebagai bank_code
            'account_holder_name' => $request->account_holder_name,
            'account_number' => $request->phone_number, // ✅ HARUS: ini nomor telepon penerima
            'description' => $request->remark ?? 'Disbursement ke DANA'
        ];




        return redirect()->route('tarik-saldo.index')->with('error', 'Pengajuan pencairan saldo ditolak.');
    }
}

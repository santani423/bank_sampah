<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Transaksi;
use App\Models\Nasabah;
use App\Models\Sampah;
use App\Models\Saldo;
use App\Models\DetailTransaksi;
use App\Models\Petugas;
use App\Models\petugasBalaceMutation;
use App\Models\petugasLog;
use App\Models\petugasTopUp;
use App\Models\saldoPetugas;
use App\Models\SaldoUtama;
use App\Models\Setting;
use App\Models\TokenWhatsApp;
use App\Models\TopUpAdmin;
use Barryvdh\DomPDF\Facade as PDF;
use RealRashid\SweetAlert\Facades\Alert;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Services\WhatsAppService; // ✅ Tambahkan ini

class TransaksiController extends Controller
{
    protected InvoiceApi $invoiceApi;
    protected $whatsappService;

    // ✅ Injeksi service WhatsApp melalui konstruktor
    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;

        $apiKey = config('xendit.api_key');

        if (empty($apiKey)) {
            Log::error('Xendit API Key not set in config.');
            abort(500, 'Xendit API Key tidak tersedia.');
        }

        Configuration::setXenditKey($apiKey);
        $this->invoiceApi = new InvoiceApi();
    }

    public function index()
    {
        $transaksis = Transaksi::with(['nasabah', 'detailTransaksi.sampah'])->paginate(10);

        foreach ($transaksis as $transaksi) {
            $transaksi->total_berat = $transaksi->detailTransaksi->sum('berat_kg');
            $transaksi->total_transaksi = $transaksi->detailTransaksi->sum('harga_total');
        }

        return view('pages.petugas.transaksi.index', compact('transaksis'));
    }

    public function generateUniqueTransactionCode()
    {
        // Format: BS-YYYYMMDD-SET-001
        $today = now()->format('Ymd');
        $prefix = "BSR-{$today}-SET-";

        // Cari kode transaksi terakhir hari ini
        $lastTransaction = Transaksi::where('kode_transaksi', 'like', $prefix . '%')
            ->orderBy('kode_transaksi', 'desc')
            ->first();

        if (!$lastTransaction) {
            // Jika belum ada transaksi hari ini, mulai dari 001
            return $prefix . '001';
        }

        // Ekstrak nomor urut terakhir
        $lastNumber = substr($lastTransaction->kode_transaksi, -3);
        $newNumber = str_pad((int)$lastNumber + 1, 3, '0', STR_PAD_LEFT);

        return $prefix . $newNumber;
    }


    public function create(Request $request)
    {
        $kodeTransaksi = $this->generateUniqueTransactionCode();
        $stokSampah = Sampah::all();
        // Ambil daftar nasabah yang terkait dengan petugas yang sedang login
        // Menggunakan join untuk mendapatkan nasabah yang dikelola oleh petugas cabang
        // Pastikan petugas yang sedang login memiliki akses ke cabang yang sesuai

        $nasabah =  Nasabah::where('no_registrasi', $request->no_registrasi)->first();

        $query = Nasabah::with('saldo');

        $query->join('cabangs', 'nasabah.cabang_id', '=', 'cabangs.id')
            ->join('petugas_cabangs', 'cabangs.id', '=', 'petugas_cabangs.cabang_id')
            ->join('petugas', 'petugas_cabangs.petugas_id', '=', 'petugas.id');

        // Hindari data nasabah dobel dengan distinct
        $nasabahList = $query->select('nasabah.*')->get();
        // dd($nasabahList);

        return view('pages.petugas.transaksi.create', compact('nasabahList', 'stokSampah', 'kodeTransaksi', 'nasabah'));
    }

    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'kode_transaksi' => 'required|string|unique:transaksi,kode_transaksi',
            'nasabah_id' => 'required|exists:nasabah,id',
            'tanggal_transaksi' => 'required|date',
            'detail_transaksi' => 'required|array|min:1',
            'detail_transaksi.*.sampah_id' => 'required|exists:sampah,id',
            'detail_transaksi.*.berat_kg' => 'required|numeric|min:0',
            'detail_transaksi.*.harga_per_kg' => 'required|numeric|min:0',
        ]);
        $totalTransaksi = 0; // Untuk menghitung total nilai transaksi

        // Iterasi detail transaksi
        foreach ($request->detail_transaksi as $detail) {
            $hargaTotal = $detail['berat_kg'] * $detail['harga_per_kg'];
            $totalTransaksi += $hargaTotal;
        }
        $saldoPetugas = SaldoPetugas::join('petugas', 'saldo_petugas.petugas_id', '=', 'petugas.id')
            ->where('petugas.email', auth()->user()->email)
            ->select('saldo_petugas.*')
            ->first();

        if ($saldoPetugas->saldo < $totalTransaksi) {
            return back()->with('error', 'Saldo petugas tidak mencukupi untuk melakukan transaksi ini.');
        }
        // Ambil ID petugas dari sesi pengguna yang sedang login
        $petugas_id = auth()->user()->id;

        // Simpan transaksi utama
        $transaksi = Transaksi::create([
            'kode_transaksi' => $request->kode_transaksi,
            'nasabah_id' => $request->nasabah_id,
            'petugas_id' => $petugas_id,
            'tanggal_transaksi' => $request->tanggal_transaksi,
        ]);

        $totalTransaksi = 0; // Untuk menghitung total nilai transaksi

        // Iterasi detail transaksi
        foreach ($request->detail_transaksi as $detail) {
            $hargaTotal = $detail['berat_kg'] * $detail['harga_per_kg'];
            $totalTransaksi += $hargaTotal;

            // Simpan detail transaksi
            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'sampah_id' => $detail['sampah_id'],
                'berat_kg' => $detail['berat_kg'],
                'harga_per_kg' => $detail['harga_per_kg'],
                'harga_total' => $hargaTotal,
            ]);
        }

        // Perbarui saldo nasabah
        $saldo = Saldo::where('nasabah_id', $request->nasabah_id)->first();
        if ($saldo) {
            $saldo->increment('saldo', $totalTransaksi);
        } else {
            Saldo::create([
                'nasabah_id' => $request->nasabah_id,
                'saldo' => $totalTransaksi,
            ]);
        }


        $sldPtgs = SaldoPetugas::where('id', $saldoPetugas->id)->first();
        $sldPtgs->saldo = $sldPtgs->saldo - $totalTransaksi;

        $sldPtgs->save();

        // Redirect ke halaman cetak nota transaksi
        // return redirect()->route('petugas.transaksi.print', ['transaksi' => $transaksi->id])
        //     ->with([
        //         'success' => 'Transaksi berhasil disimpan',
        //         'transaksi_id' => $transaksi->id,
        //     ]);
        return redirect()->route('petugas.nasabah.index', ['transaksi' => $transaksi->id])
            ->with([
                'success' => 'Transaksi berhasil disimpan',
                'transaksi_id' => $transaksi->id,
            ]);
    }
    public function print($id)
    {
        $transaksi = Transaksi::with(['nasabah', 'petugas', 'detailTransaksi.sampah'])->findOrFail($id);

        $total_transaksi = $transaksi->detailTransaksi->sum(function ($detail) {
            return $detail->berat_kg * $detail->harga_per_kg;
        });

        $data = [
            'tanggal_transaksi' => \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('Y-m-d'),
            'nasabah' => $transaksi->nasabah,
            'petugas' => $transaksi->petugas,
            'details' => $transaksi->detailTransaksi,
            'total_transaksi' => $total_transaksi
        ];

        return view('pages.petugas.transaksi.print', $data);
    }

    public function show($id)
    {
        $transaksi = Transaksi::with(['nasabah', 'detailTransaksi.sampah'])
            ->findOrFail($id);

        $detailTransaksi = $transaksi->detailTransaksi;

        return view('pages.petugas.transaksi.show', compact('transaksi', 'detailTransaksi'));
    }

    public function destroy($id)
    {
        // Cari transaksi beserta detailnya
        $transaksi = Transaksi::with('detailTransaksi.sampah')->findOrFail($id);

        // Lakukan penghapusan dalam satu proses
        $transaksi->load('detailTransaksi.sampah'); // Pastikan data relasi dimuat

        // Gunakan Eloquent untuk pengembalian stok
        foreach ($transaksi->detailTransaksi as $detail) {
            $detail->sampah->increment('stok_kg', $detail->berat_kg);
        }

        // Hapus detail transaksi dan transaksi utama
        $transaksi->detailTransaksi()->delete(); // Hapus semua detail transaksi
        $transaksi->delete(); // Hapus transaksi utama

        Alert::success('Hore!', 'Transaksi berhasil dihapus!')->autoclose(3000);

        return redirect()->route('petugas.transaksi.index');
    }


    public function topUp()
    {
        return view('pages.petugas.transaksi.topUp',);
    }

    public function createTransaction(Request $request)
    {
        $validated = $request->validate([
            'jumlah' => 'required|numeric|min:1000',
        ]);

        $user = Auth::user(); // Ambil user yang sedang login
        $order_id = 'invoice-' . time() . '-' . Str::random(9);
        $params = [
            'external_id' => $order_id,
            'amount' => (int) $validated['jumlah'],
            'payer_email' => $user->email,
            'description' => 'Pembayaran oleh ' . $user->name,
        ];

        try {
            $invoice = $this->invoiceApi->createInvoice(create_invoice_request: $params);
            $auth = auth()->user();
            $petugas  = Petugas::where('email', $auth->email)->first();
            petugasLog::create([
                'petugas_id' =>  $petugas->id,
                'activity' => 'Top Up Create Transaction',
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'description' => 'Membuat transaksi top up dengan nominal: ' . $validated['jumlah'],
            ]);


            $data = new petugasTopUp();
            $data->petugas_id = $petugas->id;
            $data->order_id = $order_id;
            $data->amount = $validated['jumlah'];
            $data->save();
            return redirect()->away($invoice['invoice_url'] ?? '/');
        } catch (Exception $e) {
            Log::error('Xendit error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withErrors(['xendit_error' => 'Gagal membuat invoice: ' . $e->getMessage()]);
        }
    }
    public function handleNotification(Request $request)
    {
        // Konfigurasi Midtrans
        // \Midtrans\Config::$serverKey = config('midtrans.server_key');
        // \Midtrans\Config::$isProduction = config('midtrans.is_production');
        // \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        // \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

        // // Terima notifikasi dari Midtrans
        // $notification = new Notification();

        // $transactionStatus = $notification->transaction_status;
        // $paymentType = $notification->payment_type;
        // $orderId = $notification->order_id;
        // $fraudStatus = $notification->fraud_status;

        // // Log untuk debugging (opsional)
        // Log::info('Midtrans Notification:', [
        //     'order_id' => $orderId,
        //     'transaction_status' => $transactionStatus,
        //     'payment_type' => $paymentType,
        //     'fraud_status' => $fraudStatus,
        // ]);

        // Update status transaksi di database (asumsikan ada tabel orders)
        // $order = \App\Models\Order::where('order_id', $orderId)->first();

        // if ($order) {
        //     switch ($transactionStatus) {
        //         case 'capture':
        //             if ($paymentType == 'credit_card') {
        //                 if ($fraudStatus == 'challenge') {
        //                     $order->status = 'challenge';
        //                 } else {
        //                     $order->status = 'success';
        //                 }
        //             }
        //             break;

        //         case 'settlement':
        //             $order->status = 'success';
        //             break;

        //         case 'pending':
        //             $order->status = 'pending';
        //             break;

        //         case 'deny':
        //             $order->status = 'deny';
        //             break;

        //         case 'expire':
        //             $order->status = 'expire';
        //             break;

        //         case 'cancel':
        //             $order->status = 'cancel';
        //             break;
        //     }

        //     $order->save();
        // }

        return response()->json(['message' => 'Notification processed successfully']);
    }


    public function callback(Request $request)
    {
        try {
            Log::info('📩 Xendit Callback Received (Admin TopUp)', $request->all());

            $payload = $request->all();

            // =========================
            // VALIDASI PAYLOAD WAJIB
            // =========================
            if (empty($payload['external_id']) || empty($payload['status'])) {
                Log::warning('⚠️ Invalid Xendit Callback Payload', $payload);
                return response()->json(['message' => 'Invalid callback payload'], 400);
            }

            $externalId = $payload['external_id'];
            $xenditStatus = strtoupper($payload['status']);

            // Mapping status Xendit → internal
            $incomingStatus = match ($xenditStatus) {
                'PAID', 'SETTLED' => 'paid',
                'PENDING' => 'pending',
                'EXPIRED' => 'expired',
                'FAILED' => 'failed',
                default => 'pending',
            };

            DB::beginTransaction();

            // =========================
            // AMBIL TRANSAKSI (LOCK)
            // =========================
            $transaction = TopUpAdmin::where('xendit_external_id', $externalId)
                ->lockForUpdate()
                ->first();

            if (!$transaction) {
                DB::rollBack();
                Log::warning('🚫 TopUp Admin tidak ditemukan.', [
                    'external_id' => $externalId
                ]);
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            // =========================
            // IDEMPOTENCY CHECK
            // =========================
            if (in_array($transaction->status, ['paid', 'success', 'settlement'])) {
                DB::commit();
                Log::info('🟢 Callback diabaikan (sudah diproses).', [
                    'external_id' => $externalId,
                    'status' => $transaction->status,
                ]);
                return response()->json(['message' => 'Already processed'], 200);
            }

            // =========================
            // UPDATE STATUS TRANSAKSI
            // =========================
            $transaction->status = $incomingStatus;
            $transaction->save();

            // =========================
            // JIKA PAID → TAMBAH SALDO
            // =========================
            if ($incomingStatus === 'paid') {

                $saldoUtama = SaldoUtama::lockForUpdate()->first();

                if (!$saldoUtama) {
                    DB::rollBack();
                    Log::error('❌ Saldo utama tidak ditemukan.');
                    return response()->json(['message' => 'Saldo utama tidak ditemukan'], 500);
                }

                $oldSaldo = $saldoUtama->saldo;
                $saldoUtama->saldo += (float) $payload['adjusted_received_amount'];
                $saldoUtama->save();

                Log::info('✅ Saldo utama bertambah (Admin TopUp)', [
                    'old_saldo' => $oldSaldo,
                    'new_saldo' => $saldoUtama->saldo,
                    'topup_amount' => $payload['adjusted_received_amount'],
                    'external_id' => $externalId,
                ]);

                // =========================
                // NOTIFIKASI WHATSAPP ADMIN
                // =========================
                $setting = Setting::first();

                if ($setting) {
                    $pesanAdmin =
                        "🔔 *TOP UP SALDO ADMIN BERHASIL*\n\n" .
                        "Pembayaran Top Up Admin telah berhasil diverifikasi.\n\n" .
                        "────────────────────────\n" .
                        "📌 *Detail Transaksi*\n" .
                        "────────────────────────\n" .
                        "• Reference ID : {$externalId}\n" .
                        "• Metode Pembayaran : {$payload['payment_method']}\n" .
                        "• Bank : {$payload['bank_code']}\n" .
                        "• Jumlah Dibayar : Rp " . number_format($payload['adjusted_received_amount'], 0, ',', '.') . "\n" .
                        "• Waktu Pembayaran : " . now()->format('d M Y H:i') . "\n\n" .
                        "────────────────────────\n" .
                        "💰 *Saldo Utama*\n" .
                        "────────────────────────\n" .
                        "• Saldo Sebelum : Rp " . number_format($oldSaldo, 0, ',', '.') . "\n" .
                        "• Saldo Setelah : Rp " . number_format($saldoUtama->saldo, 0, ',', '.') . "\n\n" .
                        "_Pesan ini dikirim otomatis oleh sistem._";

                    $this->whatsappService->sendMessage(
                        $setting->no_notifikasi,
                        $pesanAdmin
                    );
                }
            }

            DB::commit();

            Log::info('💾 Callback Xendit berhasil diproses.', [
                'external_id' => $externalId,
                'status' => $incomingStatus,
            ]);

            return response()->json(['message' => 'Callback processed successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('💥 Callback Xendit Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['message' => 'Internal server error'], 500);
        }
    }



    public function callbackPetugas(Request $request)
    {
        try {
            Log::info('📩 Xendit Callback Received (Petugas TopUp):', $request->all());

            $data = $request->all();
            if (empty($data['external_id']) || empty($data['status'])) {
                Log::warning('⚠️ Xendit Callback Missing Fields (Petugas TopUp)', $data);
                return response()->json(['message' => 'Invalid data'], 400);
            }

            DB::beginTransaction();
            $transaction = petugasTopUp::where('order_id', $data['external_id'])->lockForUpdate()->first();
            if (!$transaction) {
                DB::rollBack();
                Log::warning('🚫 Transaksi petugas tidak ditemukan untuk order_id: ' . $data['external_id']);
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            // Idempotency: jika sudah paid / success jangan proses ulang
            if (in_array(strtolower($transaction->status), ['paid', 'success', 'settlement'])) {
                DB::commit();
                Log::info('🟢 Callback petugas diabaikan karena sudah diproses.', [
                    'order_id' => $transaction->order_id,
                    'existing_status' => $transaction->status
                ]);
                return response()->json(['message' => 'Already processed'], 200);
            }

            $incomingStatus = strtolower($data['status']);
            $transaction->status = $incomingStatus;
            $transaction->save();

            if ($incomingStatus === 'paid') {
                $saldo = saldoPetugas::where('petugas_id', $transaction->petugas_id)->lockForUpdate()->first();
                if ($saldo) {
                    $oldSaldo = $saldo->saldo;
                    $saldo->saldo += $transaction->amount;
                    $saldo->save();
                    Log::info('✅ Saldo petugas bertambah (Petugas TopUp).', [
                        'petugas_id' => $transaction->petugas_id,
                        'old_saldo' => $oldSaldo,
                        'new_saldo' => $saldo->saldo,
                        'topup_amount' => $transaction->amount,
                        'order_id' => $transaction->order_id
                    ]);
                } else {
                    Log::warning('⚠️ Saldo petugas tidak ditemukan saat callback. Petugas ID: ' . $transaction->petugas_id);
                }
            }

            DB::commit();
            Log::info('💾 Transaksi petugas diperbarui.', [
                'order_id' => $transaction->order_id,
                'status' => $transaction->status
            ]);
            return response()->json(['message' => 'Callback processed'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('💥 Callback Xendit error (Petugas TopUp): ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }
}
// {
//   "id": "evt_4f21bc9a88",
//   "event": "payment.completed",
//   "created": "2026-01-01T10:05:00.000Z",
//   "business_id": "65fa9e1c7a12",
//   "data": {
//     "id": "TOPUP-20260101113204-1-69565b3472eca",
//     "reference": "TOPUP-20260101113204-1-69565b3472eca",
//     "amount": 150000,
//     "currency": "IDR",
//     "status": "paid",
//     "payment_method": "EWALLET",
//     "channel_code": "DANA"
//   }
// }
// http://127.0.0.1:8000/api/callback

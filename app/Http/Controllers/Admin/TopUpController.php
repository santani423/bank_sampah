<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TopUpAdmin;
use App\Models\SaldoUtama;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;

class TopUpController extends Controller
{
    protected InvoiceApi $invoiceApi;

    public function __construct()
    {
        $apiKey = config('xendit.api_key');

        if (empty($apiKey)) {
            Log::error('Xendit API Key not set in config.');
            abort(500, 'Xendit API Key tidak tersedia.');
        }

        Configuration::setXenditKey($apiKey);
        $this->invoiceApi = new InvoiceApi();
    }

    /**
     * Menampilkan daftar top up & saldo utama
     */
    public function index()
    {
        $topups = TopUpAdmin::with('user')
            ->latest()
            ->paginate(20);

        // Pastikan saldo utama tersedia
        $saldoUtama = SaldoUtama::firstOrCreate(
            [],
            ['saldo' => 0, 'keterangan' => 'Saldo Awal']
        );

        return view('pages.admin.topup.index', compact('topups', 'saldoUtama'));
    }

    /**
     * Form pembuatan top up
     */
    public function create()
    {
        $user = Auth::user();
        
        // Cek apakah ada top up yang masih pending
        $pendingTopup = TopUpAdmin::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        return view('pages.admin.topup.create', compact('pendingTopup'));
    }

    /**
     * Simpan dan buat invoice Xendit baru
     */
    public function store(Request $request)
    {
          return response()->json([
                'success' => false,
                'message' => 'Gagal membuat top upasdasdasd'  
            ], 500);
        // Validasi input
        $validated = $request->validate([
            'jumlah' => 'required|numeric|min:10000|max:1000000000000000000',
            'keterangan' => 'nullable|string|max:500',
        ], [
            'jumlah.required' => 'Jumlah top up harus diisi',
            'jumlah.numeric' => 'Jumlah harus berupa angka',
            'jumlah.min' => 'Minimal top up adalah Rp 10.000',
            'jumlah.max' => 'Maksimal top up adalah Rp 1.000.000.000.000.000.000',
            'keterangan.max' => 'Keterangan maksimal 500 karakter',
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();

            if (!$user->email) {
                throw new \Exception('Email user tidak ditemukan. Silakan lengkapi profil Anda terlebih dahulu.');
            }

            // Cegah top up ganda yang masih pending
            $pendingTopup = TopUpAdmin::where('user_id', $user->id)
                ->where('status', 'pending')
                ->first();

            if ($pendingTopup) {
                throw new \Exception('Anda masih memiliki top up yang belum dibayar. Selesaikan terlebih dahulu sebelum membuat yang baru.');
            }

            // Generate external ID unik
            $externalId = sprintf('TOPUP-%s-%d-%s', date('YmdHis'), $user->id, uniqid());

            // Buat invoice di Xendit
            $invoiceData = [
                'external_id' => $externalId,
                'amount' => (float) $validated['jumlah'],
                'payer_email' => $user->email,
                'description' => 'Top Up Saldo Bank Sampah' .
                    (!empty($validated['keterangan']) ? ' - ' . $validated['keterangan'] : ''),
                'invoice_duration' => 86400, // 24 jam
                'currency' => 'IDR',
                'success_redirect_url' => route('admin.topup.success'),
                'failure_redirect_url' => route('admin.topup.index'),
            ];

            Log::info('Creating Xendit invoice', [
                'external_id' => $externalId,
                'amount' => $validated['jumlah'],
                'user_id' => $user->id,
            ]);

            $invoice = $this->invoiceApi->createInvoice($invoiceData);

            if (empty($invoice['id']) || empty($invoice['invoice_url'])) {
                throw new \Exception('Response dari Xendit tidak valid.');
            }

            // Simpan top up ke database
            $topup = TopUpAdmin::create([
                'user_id' => $user->id,
                'jumlah' => $validated['jumlah'],
                'metode_pembayaran' => 'xendit',
                'status' => 'pending',
                'xendit_invoice_id' => $invoice['id'],
                'xendit_invoice_url' => $invoice['invoice_url'],
                'xendit_external_id' => $externalId,
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            DB::commit();

            Log::info('Top up created successfully', [
                'topup_id' => $topup->id,
                'invoice_id' => $invoice['id'],
            ]);

            // Redirect ke halaman pembayaran Xendit
            return redirect()->away($invoice['invoice_url']);
        } catch (\Xendit\XenditSdkException $e) {
            DB::rollBack();
            Log::error('Xendit SDK Error', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            return back()->with('error', 'Gagal terhubung ke payment gateway. Silakan coba lagi.!')->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Top up store error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
            ]);

            return back()->with('error', 'Gagal membuat top up: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * API endpoint untuk membuat top up (via AJAX)
     */
    public function apiStore(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'jumlah' => 'required|numeric|min:10000|max:100000000',
            'keterangan' => 'nullable|string|max:500',
            'force_new' => 'nullable|boolean', // Parameter untuk force create new
        ], [
            'jumlah.required' => 'Jumlah top up harus diisi',
            'jumlah.numeric' => 'Jumlah harus berupa angka',
            'jumlah.min' => 'Minimal top up adalah Rp 10.000',
            'jumlah.max' => 'Maksimal top up adalah Rp 100.000.000',
            'keterangan.max' => 'Keterangan maksimal 500 karakter',
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();

            if (!$user->email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email user tidak ditemukan. Silakan lengkapi profil Anda terlebih dahulu.'
                ], 400);
            }

            // Cek apakah ada top up yang masih pending
            $pendingTopup = TopUpAdmin::where('user_id', $user->id)
                ->where('status', 'pending')
                ->first();

            // Jika ada pending dan user belum konfirmasi untuk membuat baru
            if ($pendingTopup && !$request->input('force_new', false)) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'has_pending' => true,
                    'pending_topup' => [
                        'id' => $pendingTopup->id,
                        'jumlah' => $pendingTopup->jumlah,
                        'invoice_url' => $pendingTopup->xendit_invoice_url,
                        'created_at' => $pendingTopup->created_at->format('d M Y H:i'),
                    ],
                    'message' => 'Anda masih memiliki transaksi top up yang belum dibayar.'
                ], 409); // 409 Conflict
            }

            // Jika user memilih membuat baru, hapus/expire pending yang lama
            if ($pendingTopup && $request->input('force_new', false)) {
                $pendingTopup->update(['status' => 'expired']);
                Log::info('Expired old pending topup', ['topup_id' => $pendingTopup->id]);
            }

            // Generate external ID unik
            $externalId = sprintf('TOPUP-%s-%d-%s', date('YmdHis'), $user->id, uniqid());

            // Buat invoice di Xendit
            $invoiceData = [
                'external_id' => $externalId,
                'amount' => (float) $validated['jumlah'],
                'payer_email' => $user->email,
                'description' => 'Top Up Saldo Bank Sampah' .
                    (!empty($validated['keterangan']) ? ' - ' . $validated['keterangan'] : ''),
                'invoice_duration' => 86400, // 24 jam
                'currency' => 'IDR',
                'success_redirect_url' => route('admin.topup.success'),
                'failure_redirect_url' => route('admin.topup.index'),
            ];

            Log::info('Creating Xendit invoice via API', [
                'external_id' => $externalId,
                'amount' => $validated['jumlah'],
                'user_id' => $user->id,
            ]);

            $invoice = $this->invoiceApi->createInvoice($invoiceData);

            if (empty($invoice['id']) || empty($invoice['invoice_url'])) {
                throw new \Exception('Response dari Xendit tidak valid.');
            }

            // Simpan top up ke database
            $topup = TopUpAdmin::create([
                'user_id' => $user->id,
                'jumlah' => $validated['jumlah'],
                'metode_pembayaran' => 'xendit',
                'status' => 'pending',
                'xendit_invoice_id' => $invoice['id'],
                'xendit_invoice_url' => $invoice['invoice_url'],
                'xendit_external_id' => $externalId,
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            DB::commit();

            Log::info('Top up created successfully via API', [
                'topup_id' => $topup->id,
                'invoice_id' => $invoice['id'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Top up berhasil dibuat',
                'data' => [
                    'topup_id' => $topup->id,
                    'invoice_url' => $invoice['invoice_url'],
                    'amount' => $topup->jumlah,
                ]
            ]);
        } catch (\Xendit\XenditSdkException $e) {
            DB::rollBack();
            Log::error('Xendit SDK Error (API)', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal terhubung ke payment gateway. Silakan coba lagi.!'
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Top up store error (API)', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat top up: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Callback webhook dari Xendit
     */
    public function callback(Request $request)
    {
        try {
            Log::info('Xendit callback received', ['payload' => $request->all()]);

            // Verifikasi callback token
            $callbackToken = $request->header('x-callback-token');
            if ($callbackToken !== config('xendit.callback_token')) {
                Log::warning('Invalid callback token', ['received_token' => $callbackToken]);
                return response()->json(['error' => 'Invalid callback token'], 403);
            }

            $externalId = $request->external_id;
            $status = strtoupper($request->status);

            if (!$externalId || !$status) {
                Log::error('Missing required fields in callback', [
                    'external_id' => $externalId,
                    'status' => $status,
                ]);
                return response()->json(['error' => 'Missing required fields'], 400);
            }

            $topup = TopUpAdmin::where('xendit_external_id', $externalId)->first();

            if (!$topup) {
                Log::error('Top up not found', ['external_id' => $externalId]);
                return response()->json(['error' => 'Top up not found'], 404);
            }

            DB::beginTransaction();

            switch ($status) {
                case 'PAID':
                    if ($topup->status === 'pending') {
                        $topup->update([
                            'status' => 'success',
                            'tanggal_bayar' => now(),
                        ]);

                        // Update saldo utama
                        $saldoUtama = SaldoUtama::firstOrCreate(
                            [],
                            ['saldo' => 0, 'keterangan' => 'Saldo Awal']
                        );

                        $oldSaldo = $saldoUtama->saldo;
                        $saldoUtama->saldo += $topup->jumlah;
                        $saldoUtama->keterangan = 'Top Up oleh ' . $topup->user->name . ' sebesar Rp ' . number_format($topup->jumlah, 0, ',', '.');
                        $saldoUtama->save();

                        Log::info('Top up success and saldo updated', [
                            'topup_id' => $topup->id,
                            'old_saldo' => $oldSaldo,
                            'new_saldo' => $saldoUtama->saldo,
                        ]);
                    }
                    break;

                case 'EXPIRED':
                    $topup->update(['status' => 'expired']);
                    Log::info('Top up expired', ['topup_id' => $topup->id]);
                    break;

                case 'FAILED':
                    $topup->update(['status' => 'failed']);
                    Log::info('Top up failed', ['topup_id' => $topup->id]);
                    break;
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Callback error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Halaman sukses setelah pembayaran
     */
    public function success()
    {
        return view('pages.admin.topup.success');
    }

    /**
     * Menampilkan detail top up
     */
    public function show($id)
    {
        $topup = TopUpAdmin::with('user')->findOrFail($id);
        return view('pages.admin.topup.show', compact('topup'));
    }
}

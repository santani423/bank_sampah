<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TopUpAdmin;
use App\Models\SaldoUtama;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;

class TopUpController extends Controller
{
    public function __construct()
    {
        // Set Xendit API Key
        Configuration::setXenditKey(config('xendit.secret_key'));
    }

    /**
     * Menampilkan halaman top up
     */
    public function index()
    {
        $topups = TopUpAdmin::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Ambil saldo utama
        $saldoUtama = SaldoUtama::first();
        if (!$saldoUtama) {
            $saldoUtama = SaldoUtama::create([
                'saldo' => 0,
                'keterangan' => 'Saldo Awal'
            ]);
        }

        return view('pages.admin.topup.index', compact('topups', 'saldoUtama'));
    }

    /**
     * Menampilkan form top up
     */
    public function create()
    {
        return view('pages.admin.topup.create');
    }

    /**
     * Proses create invoice Xendit
     */
    public function store(Request $request)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:10000',
            'keterangan' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $externalId = 'TOPUP-' . time() . '-' . Auth::id();

            // Create invoice Xendit
            $apiInstance = new InvoiceApi();
            
            $createInvoiceRequest = [
                'external_id' => $externalId,
                'amount' => (float) $request->jumlah,
                'payer_email' => Auth::user()->email ?? 'admin@banksampah.com',
                'description' => 'Top Up Saldo Bank Sampah - ' . ($request->keterangan ?? ''),
                'invoice_duration' => 86400, // 24 jam
                'currency' => 'IDR',
                'success_redirect_url' => route('admin.topup.success'),
                'failure_redirect_url' => route('admin.topup.index'),
            ];

            $result = $apiInstance->createInvoice($createInvoiceRequest);

            // Simpan ke database
            $topup = TopUpAdmin::create([
                'user_id' => Auth::id(),
                'jumlah' => $request->jumlah,
                'metode_pembayaran' => 'xendit',
                'status' => 'pending',
                'xendit_invoice_id' => $result['id'],
                'xendit_invoice_url' => $result['invoice_url'],
                'xendit_external_id' => $externalId,
                'keterangan' => $request->keterangan
            ]);

            DB::commit();

            // Redirect ke payment page Xendit
            return redirect($result['invoice_url']);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal membuat invoice: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Callback dari Xendit
     */
    public function callback(Request $request)
    {
        try {
            // Verifikasi callback token
            $callbackToken = $request->header('x-callback-token');
            if ($callbackToken !== config('xendit.callback_token')) {
                return response()->json(['error' => 'Invalid callback token'], 403);
            }

            $externalId = $request->external_id;
            $status = $request->status;

            $topup = TopUpAdmin::where('xendit_external_id', $externalId)->first();

            if (!$topup) {
                return response()->json(['error' => 'Top up not found'], 404);
            }

            DB::beginTransaction();

            if ($status === 'PAID' && $topup->status === 'pending') {
                // Update status top up
                $topup->update([
                    'status' => 'success',
                    'tanggal_bayar' => now()
                ]);

                // Update saldo utama
                $saldoUtama = SaldoUtama::first();
                if (!$saldoUtama) {
                    $saldoUtama = SaldoUtama::create([
                        'saldo' => 0,
                        'keterangan' => 'Saldo Awal'
                    ]);
                }

                $saldoUtama->saldo += $topup->jumlah;
                $saldoUtama->keterangan = 'Top Up oleh ' . $topup->user->name . ' sebesar Rp ' . number_format($topup->jumlah, 0, ',', '.');
                $saldoUtama->save();

            } elseif ($status === 'EXPIRED') {
                $topup->update(['status' => 'expired']);
            }

            DB::commit();

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
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

<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Transaksi;
use App\Models\Nasabah;
use App\Models\Sampah;
use App\Models\Saldo;
use App\Models\DetailTransaksi;
use App\Models\petugasBalaceMutation;
use App\Models\petugasLog;
use App\Models\petugasTopUp;
use App\Models\saldoPetugas;
use App\Models\TokenWhatsApp;
use Barryvdh\DomPDF\Facade as PDF;
use RealRashid\SweetAlert\Facades\Alert;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;

class TransaksiController extends Controller
{

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


    public function create()
    {
        $kodeTransaksi = $this->generateUniqueTransactionCode();
        $stokSampah = Sampah::all();
        // Ambil daftar nasabah yang terkait dengan petugas yang sedang login
        // Menggunakan join untuk mendapatkan nasabah yang dikelola oleh petugas cabang
        // Pastikan petugas yang sedang login memiliki akses ke cabang yang sesuai



        $query = Nasabah::with('saldo');

        $query->join('cabangs', 'nasabah.cabang_id', '=', 'cabangs.id')
            ->join('petugas_cabangs', 'cabangs.id', '=', 'petugas_cabangs.cabang_id')
            ->join('petugas', 'petugas_cabangs.petugas_id', '=', 'petugas.id');

        // Hindari data nasabah dobel dengan distinct
        $nasabahList = $query->select('nasabah.*')->distinct()->paginate(10);

        return view('pages.petugas.transaksi.create', compact('nasabahList', 'stokSampah', 'kodeTransaksi'));
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

        // Redirect ke halaman cetak nota transaksi
        return redirect()->route('petugas.transaksi.print', ['transaksi' => $transaksi->id])
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
        try {
            $auth = auth()->user();
            petugasLog::create([
                'petugas_id' => $auth->id,
                'activity' => 'Top Up Create Transaction',
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'description' => 'Membuat transaksi top up dengan nominal: ' . $request->nominal,
            ]);
            // Konfigurasi Midtrans
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = config('midtrans.is_sanitized');
            Config::$is3ds = config('midtrans.is_3ds');
            $order_id  = "TOPUP-" . uniqid();

            // Buat transaksi
            $auth = auth()->user();
            $params = [
                'transaction_details' => [
                    'order_id' => $order_id,
                    'gross_amount' => $request->nominal,
                ],
                'customer_details' => [
                    'first_name' => $auth->name ?? '',
                    'email' => $auth->email ?? '',
                    'phone' => $auth->phone ?? '',
                ]
            ];

            $data = new petugasTopUp();
            $data->petugas_id = $auth->id;
            $data->order_id = $order_id;
            $data->amount = $request->nominal;
            $data->save();

            $snapToken = Snap::getSnapToken($params);

            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            // Log error jika perlu
            Log::error('Create Transaction Error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat membuat transaksi.'], 500);
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
        // Ambil data dari request (Midtrans akan mengirim sebagai JSON body)
        $transaction_id     = $request->input('transaction_id');
        $transactionStatus  = $request->input('transaction_status');
        $paymentType        = $request->input('payment_type');
        $fraudStatus        = $request->input('fraud_status');
        $orderId            = $request->input('order_id');

        // Temukan transaksi berdasarkan order_id
        $transaction = petugasTopUp::where('order_id', $orderId)->where('transaction_id', $transaction_id)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }
        // Cegah proses callback jika transaksi sudah sukses
        if ($transaction->status === 'success') {
            return response()->json(['message' => 'Transaction already processed'], 200);
        }
        // Update data dasar transaksi
        $transaction->transaction_id = $transaction_id;
        $transaction->payment_type   = $paymentType;

        // Log aktivitas callback        
        petugasLog::create([
            'petugas_id' => $transaction->petugas_id,
            'activity' => 'Top Up Callback',
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'description' => "Callback Midtrans untuk order_id: $orderId, status: $transactionStatus",
        ]);

        // Penanganan status dari Midtrans
        switch ($transactionStatus) {
            case 'capture':
                if ($paymentType === 'credit_card' && $fraudStatus === 'challenge') {
                    $transaction->status = 'pending';
                } else {
                    $transaction->status = 'success';
                }
                break;

            case 'settlement':
                $transaction->status = 'success';

                // Update saldo petugas
                $saldo = saldoPetugas::firstOrCreate(
                    ['petugas_id' => $transaction->petugas_id],
                    ['saldo' => 0]
                );

                $saldo->increment('saldo', $transaction->amount);

                petugasBalaceMutation::create([
                    'petugas_id'   =>  $transaction->petugas_id,
                    'amount'   =>  $transaction->amount,
                    'type'   =>  'credit',
                    'source'   =>  'topup',
                    'description' => "Callback Midtrans untuk order_id: $orderId, status: $transactionStatus",
                ]);
                break;

            case 'pending':
                $transaction->status = 'pending';
                break;

            case 'deny':
            case 'expire':
            case 'cancel':
                $transaction->status = 'failed';
                break;

            case 'refund':
                $transaction->status = 'refund';
                break;

            case 'partial_refund':
                $transaction->status = 'partial_refund';
                break;

            case 'authorize':
                $transaction->status = 'authorize';
                break;

            default:
                $transaction->status = $transactionStatus;
                break;
        }

        $transaction->save();

        return response()->json(['message' => 'Callback processed successfully']);
    }
}

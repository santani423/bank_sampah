<?php

namespace App\Http\Controllers;

use App\Models\PengirimanLapak;
use Illuminate\Http\Request;
use App\Helpers\FileHelper;
use App\Models\DetailPengirimanLapak;
use App\Models\Lapak;
use App\Models\TransaksiLapak;
use App\Models\PencairanLapak;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Services\WhatsAppService; // ✅ Tambahkan ini


class PengirimanLapakController extends Controller
{


    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);

            return response()->json([
                'success' => 'Gambar berhasil diupload!',
                'image_name' => $name
            ]);
        }

        return response()->json(['error' => 'Gagal upload gambar.'], 400);
    }
    /**
     * Display a listing of the resource.
     */
    public function finalisasi(Request $request, $id)
    {
        $request->validate([
            'kode_lapak'          => 'required|exists:lapak,kode_lapak',
            'kode_pengiriman'     => 'required|string|unique:pengiriman_lapaks,kode_pengiriman',
            'tanggal_pengiriman'  => 'required|date',
            'driver'              => 'required|string|max:100',
            'driver_hp'           => 'required|string|max:20',
            'plat_nomor'          => 'required|string|max:20',
            'petugas_id'          => 'required|exists:users,id',
            'gudang_id'           => 'required|exists:gudangs,id',
            'foto_sampah'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_plat'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            // Pesan validasi
            'kode_lapak.required'         => 'Kode lapak wajib diisi.',
            'kode_lapak.exists'           => 'Kode lapak tidak ditemukan.',
            'kode_pengiriman.required'    => 'Kode pengiriman wajib diisi.',
            'kode_pengiriman.unique'      => 'Kode pengiriman sudah digunakan.',
            'tanggal_pengiriman.required' => 'Tanggal pengiriman wajib diisi.',
            'tanggal_pengiriman.date'     => 'Format tanggal pengiriman tidak valid.',
            'driver.required'             => 'Nama sopir wajib diisi.',
            'driver_hp.required'          => 'Nomor HP sopir wajib diisi.',
            'plat_nomor.required'         => 'Nomor plat kendaraan wajib diisi.',
            'petugas_id.required'         => 'Petugas wajib dipilih.',
            'petugas_id.exists'           => 'Petugas tidak ditemukan.',
            'gudang_id.required'          => 'Gudang tujuan wajib dipilih.',
            'gudang_id.exists'            => 'Gudang tujuan tidak ditemukan.',
            'foto_sampah.image'           => 'Foto muatan harus berupa gambar.',
            'foto_plat.image'             => 'Foto plat nomor harus berupa gambar.',
        ]);
        // Cari data pengiriman (atau buat baru jika perlu)
        $lapak = Lapak::where('kode_lapak', $request->kode_lapak)->firstOrFail();

        $transaksiPending = $lapak->transaksiPending()->get();

        $pengiriman =   new PengirimanLapak();

        // Upload foto sampah jika ada
        if ($request->hasFile('foto_sampah')) {
            $path = FileHelper::storeImageByDate($request->file('foto_sampah'), 'pengiriman');

            // Simpan path relatif ke database
            $pengiriman->foto_muatan = $path;
        }



        // Upload foto plat nomor jika ada
        if ($request->hasFile('foto_plat')) {
            $path = FileHelper::storeImageByDate($request->file('foto_plat'), 'pengiriman');
            $pengiriman->foto_plat_nomor = $path;
        }

        // Update status
        $pengiriman->kode_pengiriman = $request->kode_pengiriman;
        $pengiriman->lapak_id = $lapak->id;
        $pengiriman->tanggal_pengiriman = date('Y-m-d', strtotime($request->tanggal_pengiriman));
        $pengiriman->driver = $request->driver;
        $pengiriman->driver_hp = $request->driver_hp;
        $pengiriman->plat_nomor = $request->plat_nomor;
        $pengiriman->petugas_id = $request->petugas_id;
        $pengiriman->gudang_id = $request->gudang_id;
        $pengiriman->status_pengiriman = 'dikirim';

        // Simpan ke database
        $pengiriman->save();


        foreach ($transaksiPending as $transaksi) {

            $detailPengirimanLapak = new DetailPengirimanLapak();
            $detailPengirimanLapak->pengiriman_lapak_id = $pengiriman->id;
            $detailPengirimanLapak->petugas_id =  $request->petugas_id;
            $detailPengirimanLapak->transaksi_lapak_id =  $transaksi->id;
            $detailPengirimanLapak->save();

            $transaksi->status_transaksi = 'dikirim';
            $transaksi->save();
        }


        return response()->json([
            'status' => 'success',
            'message' => 'Pengiriman berhasil difinalisasi.',
            'pengiriman' => $pengiriman
        ]);
    }

    public function pengirimanPending(Request $request)
    {
        // Jumlah data per halaman (default 10)
        $perPage = $request->get('per_page', 10);

        $pengiriman = PengirimanLapak::with([
            'detailPengirimanLapaks.transaksiLapak.lapak',
            'gudang.cabang',
            'petugas'
        ])
            ->where('status_pengiriman', 'dikirim')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success'  => true,
            'message' => 'Data pengiriman pending berhasil diambil.',
            'data'    => $pengiriman->items(),
            'pagination' => [
                'current_page' => $pengiriman->currentPage(),
                'last_page' => $pengiriman->lastPage(),
                'per_page' => $pengiriman->perPage(),
                'total' => $pengiriman->total(),
                'from' => $pengiriman->firstItem(),
                'to' => $pengiriman->lastItem(),
            ]
        ]);
    }


    public function penerimaanSampahCustomer(Request $request, $id)
    {
        // =========================
        // 1. VALIDASI INPUT
        // =========================
        $validator = Validator::make($request->all(), [
            'kode_pengiriman' => 'required|string|exists:pengiriman_lapak,kode_pengiriman',
            'file_sampah' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'catatan_sampah' => 'nullable|string|max:500',
            'status_pengiriman' => 'nullable|string|max:500',
        ]);

        try {

            // Ambil data pengiriman berdasarkan kode
            $pengiriman = PengirimanLapak::with(['detailPengirimanLapaks.transaksiLapak.detailTransaksiLapak', 'gudang.cabang'])
                ->where('kode_pengiriman', $request->kode_pengiriman)
                ->first();
            // Upload foto sampah jika ada
            if ($request->hasFile('file_sampah')) {
                $path = FileHelper::storeImageByDate($request->file('file_sampah'), 'penerimaan_sampah_customer');
            }
            foreach ($pengiriman->detailPengirimanLapaks as $detailPengirimanLapaks) {

                $transaksi_lapak = TransaksiLapak::find($detailPengirimanLapaks->transaksi_lapak_id);
                $transaksi_lapak->approval = 'approved';
                $transaksi_lapak->save();
            }

            $pengiriman->status_pengiriman = 'diterima';
            $pengiriman->catatan = $request->catatan_sampah;
            $pengiriman->foto_penerimaan = $path;
            $pengiriman->status_pengiriman = $request->status_pengiriman;
            $pengiriman->save();
            return response()->json([
                'status' => true,
                'message' => 'Penerimaan sampah oleh customer berhasil dicatat.',
                'kode_pengiriman' => $pengiriman,
                'status_pengiriman' =>  $request->status_pengiriman,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memproses data.',
                'error' => $th->getMessage(), // hapus di production jika perlu
            ], 500);
        }
    }



    public function index(Request $request)
    {
        try {
            // =========================
            // 1. VALIDASI PARAMETER
            // =========================
            $perPage = (int) $request->get('per_page', 10);
            $perPage = $perPage > 0 ? $perPage : 10;

            $tanggalMulai   = $request->get('tanggal_mulai');
            $tanggalSelesai = $request->get('tanggal_selesai');

            // =========================
            // 2. QUERY BASE
            // =========================
            $query = PengirimanLapak::with([
                'detailPengirimanLapaks.transaksiLapak.lapak',
                'gudang.cabang',
                'petugas'
            ]);

            // =========================
            // 3. FILTER RANGE TANGGAL
            // =========================
            if ($tanggalMulai && $tanggalSelesai) {
                $query->whereBetween('tanggal_pengiriman', [
                    Carbon::parse($tanggalMulai)->startOfDay(),
                    Carbon::parse($tanggalSelesai)->endOfDay()
                ]);
            } elseif ($tanggalMulai) {
                $query->whereDate(
                    'tanggal_pengiriman',
                    '>=',
                    Carbon::parse($tanggalMulai)->startOfDay()
                );
            } elseif ($tanggalSelesai) {
                $query->whereDate(
                    'tanggal_pengiriman',
                    '<=',
                    Carbon::parse($tanggalSelesai)->endOfDay()
                );
            }

            if ($request->has('customer')) {
                $customer = $request->get('customer');
                $query->whereHas('gudang', function ($q) use ($customer) {
                    $q->where('nama_gudang', 'LIKE', '%' . $customer . '%');
                });
            }

            if ($request->has('cabang')) {
                $cabang = $request->get('cabang');
                $query->whereHas('gudang.cabang', function ($q) use ($cabang) {
                    $q->where('kode_cabang', $cabang);
                });
            }

            if ($request->has('status_pengiriman')) {
                $status_pengiriman = $request->get('status_pengiriman');
                $query->where('status_pengiriman', $status_pengiriman);
            }

            // =========================
            // 4. SORT & PAGINATION
            // =========================
            $pengiriman = $query
                ->latest('tanggal_pengiriman')
                ->paginate($perPage);

            // =========================
            // 5. RESPONSE SUKSES
            // =========================
            return response()->json([
                'success' => true,
                'message' => 'Data pengiriman lapak berhasil diambil.',
                'data' => $pengiriman->items(),
                'pagination' => [
                    'current_page' => $pengiriman->currentPage(),
                    'last_page'    => $pengiriman->lastPage(),
                    'per_page'     => $pengiriman->perPage(),
                    'total'        => $pengiriman->total(),
                    'from'         => $pengiriman->firstItem(),
                    'to'           => $pengiriman->lastItem(),
                ],
                'filters' => [
                    'tanggal_mulai'   => $tanggalMulai,
                    'tanggal_selesai' => $tanggalSelesai,
                ]
            ], 200);
        } catch (\Throwable $th) {

            // =========================
            // 6. LOG ERROR
            // =========================
            Log::error('Gagal mengambil data pengiriman lapak', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);

            // =========================
            // 7. RESPONSE ERROR
            // =========================
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data pengiriman lapak.',
            ], 500);
        }
    }

    public function bayarSampahLapak(WhatsAppService $wa, Request $request, $code)
    {
        try {
            $pengiriman = PengirimanLapak::with([
                'detailPengirimanLapaks.transaksiLapak.lapak',
                'gudang.cabang',
                'petugas'
            ])->where('kode_pengiriman', $code)->firstOrFail();


            $detailPengirimanLapaks = $pengiriman->detailPengirimanLapaks;
            $jenisMetodePenarikan = $pengiriman->lapak->jenisMetodePenarikan;
            $fee = 0;
            $fee_gross = $request->subtotal;
            $amount = (int) $request->subtotal;
            $externalId = 'disb-dana-lpk-' . time() . '-' . Str::random(5);
            if ($jenisMetodePenarikan->fee_bearer == 'CUSTOMER') {
                $fee = $jenisMetodePenarikan->base_fee + ($jenisMetodePenarikan->ppn_percent / 100 * $jenisMetodePenarikan->base_fee);
                $amount -= (int) $fee;
                $fee_gross -= (int) $fee;
            }



            $payload = [
                'external_id' => $externalId,
                'amount' => $amount,
                'bank_code' => $jenisMetodePenarikan->code, // contoh: DANA
                'account_holder_name' => $pengiriman->lapak->nama_lapak,
                'account_number' => $pengiriman->lapak->no_telepon, // nomor DANA
                'description' => sprintf(
                    'Pembayaran sampah lapak | Kode: %s | Lapak: %s',
                    $pengiriman->kode_pengiriman,
                    $pengiriman->lapak->nama_lapak
                ),
            ];
            $sumber_dana = '';
            if ($request->jenis_bayar == 'potong_saldo') {
                // Jika biaya ditanggung bank, tambahkan ke jumlah pencairan
                $response = Http::withBasicAuth(config('xendit.api_key'), '')
                    ->post('https://api.xendit.co/disbursements', $payload);


                if (!$response->successful()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Gagal memproses pembayaran sampah lapak.',
                        'errors' => $response->json(),
                    ], 500);
                }
                $sumber_dana = 'saldo_admin';
            } else {
                // Jika bukan potong saldo, anggap berhasil tanpa panggilan API
                $sumber_dana = 'transfer_admin';
                if ($request->hasFile('bukti_transfer')) {
                    $path = FileHelper::storeImageByDate($request->file('bukti_transfer'), 'bukti_transfer');
                }
            }


            $kode_pencairan = 'PCR-LPK' . time() . '-' . Str::upper(Str::random(6));


            $pencairan = new PencairanLapak();
            $pencairan->kode_pencairan = $kode_pencairan;
            $pencairan->lapak_id = $pengiriman->lapak->id;
            $pencairan->pengiriman_lapak_id = $pengiriman->id;
            $pencairan->metode_id = $jenisMetodePenarikan->id;
            $pencairan->jumlah_pencairan = $request->subtotal;
            $pencairan->tanggal_pengajuan = now();
            $pencairan->tanggal_proses = now();
            $pencairan->ppn_percent = $jenisMetodePenarikan->ppn_percent;
            $pencairan->fee_gross = $jenisMetodePenarikan->base_fee;
            $pencairan->total_pencairan = $amount;
            $pencairan->fee_net = $fee;
            $pencairan->fee_bearer = $jenisMetodePenarikan->fee_bearer;
            $pencairan->status = 'disetujui';
            $pencairan->keterangan = $request->catatan_sampah;
            $pencairan->save();

            $pengiriman->status_pengiriman = 'dibayar';
            $pengiriman->save();


            foreach ($detailPengirimanLapaks as $detail) {
                $transaksi_lapak = TransaksiLapak::find($detail->transaksi_lapak_id);
                $transaksi_lapak->approval = 'approved';
                $transaksi_lapak->status_transaksi = 'approved';
                $transaksi_lapak->save();
            }



            // Kirim pesan WhatsApp ke nomor lapak jika ada
            $nomorWa = $pengiriman->lapak->no_telepon ?? null;
            if ($nomorWa) {
                $nomorWa = preg_replace('/^0/', '62', $nomorWa);
                $pesanInvoice = "*INVOICE Pencairan Lapak*\n"
                    . "==============================\n"
                    . "Kode Pengiriman : {$pengiriman->kode_pengiriman}\n"
                    . "Nama Lapak      : {$pengiriman->lapak->nama_lapak}\n"
                    . "Bank            : {$pengiriman->lapak->nama_bank}\n"
                    . "No Rekening     : {$pengiriman->lapak->nomor_rekening}\n"
                    . "Atas Nama       : {$pengiriman->lapak->nama_rekening}\n"
                    . "------------------------------\n"
                    . "Total Pencairan : Rp " . number_format($request->subtotal, 0, ',', '.') . "\n"
                    . "Biaya Admin     : Rp " . number_format($pencairan->fee_net ?? 0, 0, ',', '.') . "\n"
                    . "Jumlah Diterima : Rp " . number_format(($request->subtotal ?? 0) - ($pencairan->fee_net ?? 0), 0, ',', '.') . "\n"
                    . "Tanggal         : " . ($pencairan->tanggal_proses  ?? '-') . "\n"
                    . "==============================\n"
                    . "Detail invoice: " . config('app.url') . "/invoice/pencairan-lapak/{$kode_pencairan}\n"
                    . "Terima kasih atas kepercayaannya.\nBank Sampah";

                $waResult = null;
                // Kirim gambar jika ada file bukti_transfer dan path valid
                if (!empty($path ?? null)) {
                    $appUrl = config('app.url');
                    if (str_contains($appUrl, 'localhost') || str_contains($appUrl, '127.0.0.1')) {
                        $urlBukti = 'https://fastly.picsum.photos/id/905/200/300.jpg?hmac=uLUlIwyKcu9AtTY3uOL04O0gbesMVu-yNVRvCsF1xD8';
                    } else {
                        $urlBukti = $appUrl . '/storage/' . ltrim($path, '/');
                    }
                    $waResult = $wa->sendImage(
                        $nomorWa,
                        $pesanInvoice,
                        $urlBukti
                    );
                } else {
                    $waResult = $wa->sendMessage(
                        $nomorWa,
                        $pesanInvoice
                    );
                }
                if (empty($waResult['status'])) {
                    Log::warning('Gagal kirim WhatsApp ke lapak', [
                        'nomor' => $nomorWa,
                        'wa_result' => $waResult
                    ]);
                }
            }


            return response()->json([
                'success' => true,
                'message' => 'Data pengiriman lapak berhasil diambil.',
                'data' => $pengiriman,
                'sumber_dana' => $sumber_dana,
                'wa' => $wa
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Gagal mengambil data pengiriman lapak', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data pengiriman lapak.',
            ], 500);
        }
    }
}

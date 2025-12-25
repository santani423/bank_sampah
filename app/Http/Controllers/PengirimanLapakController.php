<?php

namespace App\Http\Controllers;

use App\Models\PengirimanLapak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Helpers\FileHelper;
use App\Models\DetailPengirimanLapak;
use App\Models\Lapak;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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


    public function finalisasi(Request $request, $id)
    {
        // ================= VALIDASI =================
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

        DB::beginTransaction();

        try {
            // ================= LAPAK =================
            $lapak = Lapak::where('kode_lapak', $request->kode_lapak)->firstOrFail();

            $transaksiPending = $lapak->transaksiPending()->lockForUpdate()->get();

            if ($transaksiPending->isEmpty()) {
                throw new \Exception('Tidak ada transaksi yang menunggu untuk dikirim.');
            }

            // ================= PENGIRIMAN =================
            $pengiriman = new PengirimanLapak();
            $pengiriman->kode_pengiriman = $request->kode_pengiriman;
            $pengiriman->tanggal_pengiriman = Carbon::parse($request->tanggal_pengiriman)->toDateString();
            $pengiriman->driver = $request->driver;
            $pengiriman->driver_hp = $request->driver_hp;
            $pengiriman->plat_nomor = $request->plat_nomor;
            $pengiriman->petugas_id = $request->petugas_id;
            $pengiriman->gudang_id = $request->gudang_id;
            $pengiriman->status_pengiriman = 'dikirim';

            // ================= UPLOAD FILE =================
            if ($request->hasFile('foto_sampah')) {
                $pengiriman->foto_muatan = FileHelper::storeImageByDate(
                    $request->file('foto_sampah'),
                    'pengiriman'
                );
            }

            if ($request->hasFile('foto_plat')) {
                $pengiriman->foto_plat_nomor = FileHelper::storeImageByDate(
                    $request->file('foto_plat'),
                    'pengiriman'
                );
            }

            $pengiriman->save();

            // ================= DETAIL & UPDATE TRANSAKSI =================
            foreach ($transaksiPending as $transaksi) {
                DetailPengirimanLapak::create([
                    'pengiriman_lapak_id' => $pengiriman->id,
                    'transaksi_lapak_id'  => $transaksi->id,
                    'petugas_id'          => $request->petugas_id,
                ]);

                $transaksi->update([
                    'status_transaksi' => 'dikirim'
                ]);
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Pengiriman berhasil difinalisasi.'
            ], 200);
        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Gagal memfinalisasi pengiriman lapak', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Pengiriman gagal difinalisasi. Silakan coba kembali atau hubungi administrator.'
            ], 500);
        }
    }


    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PengirimanLapak $pengirimanLapak)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PengirimanLapak $pengirimanLapak)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PengirimanLapak $pengirimanLapak)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PengirimanLapak $pengirimanLapak)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\PengirimanLapak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Helpers\FileHelper;
use App\Models\DetailPengirimanLapak;
use App\Models\Lapak;

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
        // Validasi input dengan pesan bahasa Indonesia
        $request->validate([
            'kode_lapak' => 'required|exists:lapaks,kode_lapak',
            'kode_pengiriman' => 'required',
            'tanggal_pengiriman' => 'required|date',
            'driver' => 'required',
            'driver_hp' => 'required',
            'plat_nomor' => 'required',
            'petugas_id' => 'required|exists:users,id',
            'gudang_id' => 'required|exists:gudangs,id',
        ], [
            'kode_lapak.required' => 'Kode lapak wajib diisi.',
            'kode_lapak.exists' => 'Kode lapak tidak ditemukan.',
            'kode_pengiriman.required' => 'Kode pengiriman wajib diisi.',
            'tanggal_pengiriman.required' => 'Tanggal pengiriman wajib diisi.',
            'tanggal_pengiriman.date' => 'Format tanggal pengiriman tidak valid.',
            'driver.required' => 'Nama driver wajib diisi.',
            'driver_hp.required' => 'Nomor HP driver wajib diisi.',
            'plat_nomor.required' => 'Plat nomor wajib diisi.',
            'petugas_id.required' => 'Petugas wajib diisi.',
            'petugas_id.exists' => 'Petugas tidak ditemukan.',
            'gudang_id.required' => 'Gudang wajib diisi.',
            'gudang_id.exists' => 'Gudang tidak ditemukan.',
        ]);

        try {
            // Cari data pengiriman (atau buat baru jika perlu)
            $lapak = Lapak::where('kode_lapak', $request->kode_lapak)->firstOrFail();
            $transaksiPending = $lapak->transaksiPending()->get();
            $pengiriman = new PengirimanLapak();

            // Upload foto sampah jika ada
            if ($request->hasFile('foto_sampah')) {
                $path = FileHelper::storeImageByDate($request->file('foto_sampah'), 'pengiriman');
                $pengiriman->foto_muatan = $path;
            }

            // Upload foto plat nomor jika ada
            if ($request->hasFile('foto_plat')) {
                $path = FileHelper::storeImageByDate($request->file('foto_plat'), 'pengiriman');
                $pengiriman->foto_plat_nomor = $path;
            }

            // Update status
            $pengiriman->kode_pengiriman = $request->kode_pengiriman;
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
                'data' => $request->all(),
                'pengiriman' => $pengiriman
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memproses finalisasi pengiriman: ' . $e->getMessage(),
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

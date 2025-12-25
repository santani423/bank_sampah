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

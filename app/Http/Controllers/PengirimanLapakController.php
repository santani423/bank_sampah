<?php

namespace App\Http\Controllers;

use App\Models\PengirimanLapak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Helpers\FileHelper;

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
        $pengiriman =   new PengirimanLapak();

        // Upload foto sampah jika ada
        if ($request->hasFile('foto_sampah')) {
            $path = FileHelper::storeImageByDate($request->file('foto_sampah'), 'pengiriman');

            // Simpan path relatif ke database
            $pengiriman->foto_sampah = $path;
        }



        // Upload foto plat nomor jika ada
        if ($request->hasFile('foto_plat')) {
            $path = FileHelper::storeImageByDate($request->file('foto_plat'), 'pengiriman');
            $pengiriman->foto_plat = $path;
        }

        // Update status
        $pengiriman->status_pengiriman = 'dikirim';

        // Simpan ke database
        // $pengiriman->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Pengiriman berhasil difinalisasi.',
            'data' => $request->all()
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

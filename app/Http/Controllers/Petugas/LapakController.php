<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Lapak;
use App\Models\Cabang;
use App\Models\Petugas;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class LapakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil cabang petugas yang sedang login
        $petugasCabangIds = $this->getPetugasCabangIds();
        
        $query = Lapak::with('cabang')
            ->whereIn('cabang_id', $petugasCabangIds);

        // Filter berdasarkan nama lapak
        if ($request->filled('nama_lapak')) {
            $query->where('nama_lapak', 'like', '%' . $request->input('nama_lapak') . '%');
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $lapaks = $query->paginate(10);
        
        return view('pages.petugas.lapak.index', compact('lapaks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil cabang yang terkait dengan petugas yang sedang login
        $petugasCabangIds = $this->getPetugasCabangIds();
        $cabangs = Cabang::whereIn('id', $petugasCabangIds)
            ->where('status', 'aktif')
            ->orderBy('nama_cabang')
            ->get();
        
        // Generate kode lapak otomatis
        $lastLapak = Lapak::latest('id')->first();
        $nextNumber = $lastLapak ? intval(substr($lastLapak->kode_lapak, 3)) + 1 : 1;
        $kodeLapak = 'LPK' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return view('pages.petugas.lapak.create', compact('cabangs', 'kodeLapak'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cabang_id' => 'required|exists:cabangs,id',
            'kode_lapak' => 'required|unique:lapak,kode_lapak|max:50',
            'nama_lapak' => 'required|string|max:100',
            'alamat' => 'required|string',
            'kota' => 'nullable|string|max:50',
            'provinsi' => 'nullable|string|max:50',
            'kode_pos' => 'nullable|string|max:10',
            'no_telepon' => 'nullable|string|max:20',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        $data = $request->all();

        // Upload foto jika ada
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/lapak'), $filename);
            $data['foto'] = $filename;
        }

        Lapak::create($data);

        Alert::success('Berhasil', 'Data lapak berhasil ditambahkan');
        return redirect()->route('petugas.lapak.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lapak = Lapak::with('cabang')->findOrFail($id);
        return view('pages.petugas.lapak.show', compact('lapak'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $lapak = Lapak::findOrFail($id);
        
        // Ambil cabang yang terkait dengan petugas yang sedang login
        $petugasCabangIds = $this->getPetugasCabangIds();
        $cabangs = Cabang::whereIn('id', $petugasCabangIds)
            ->where('status', 'aktif')
            ->orderBy('nama_cabang')
            ->get();
        
        return view('pages.petugas.lapak.edit', compact('lapak', 'cabangs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $lapak = Lapak::findOrFail($id);

        $request->validate([
            'cabang_id' => 'required|exists:cabangs,id',
            'kode_lapak' => 'required|max:50|unique:lapak,kode_lapak,' . $id,
            'nama_lapak' => 'required|string|max:100',
            'alamat' => 'required|string',
            'kota' => 'nullable|string|max:50',
            'provinsi' => 'nullable|string|max:50',
            'kode_pos' => 'nullable|string|max:10',
            'no_telepon' => 'nullable|string|max:20',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        $data = $request->all();

        // Upload foto baru jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($lapak->foto && file_exists(public_path('uploads/lapak/' . $lapak->foto))) {
                unlink(public_path('uploads/lapak/' . $lapak->foto));
            }

            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/lapak'), $filename);
            $data['foto'] = $filename;
        } else {
            unset($data['foto']);
        }

        $lapak->update($data);

        Alert::success('Berhasil', 'Data lapak berhasil diperbarui');
        return redirect()->route('petugas.lapak.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lapak = Lapak::findOrFail($id);

        // Hapus foto jika ada
        if ($lapak->foto && file_exists(public_path('uploads/lapak/' . $lapak->foto))) {
            unlink(public_path('uploads/lapak/' . $lapak->foto));
        }

        $lapak->delete();

        Alert::success('Berhasil', 'Data lapak berhasil dihapus');
        return redirect()->route('petugas.lapak.index');
    }

    /**
     * Helper method untuk mendapatkan ID cabang yang terkait dengan petugas yang sedang login
     */
    private function getPetugasCabangIds()
    {
        $petugas = Petugas::where('email', auth()->user()->email)->first();
        
        if (!$petugas) {
            return [];
        }

        return DB::table('petugas_cabangs')
            ->where('petugas_id', $petugas->id)
            ->pluck('cabang_id')
            ->toArray();
    }
}

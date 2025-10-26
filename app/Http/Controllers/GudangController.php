<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\GudangsImport;

class GudangController extends Controller
{
    /**
     * Tampilkan daftar gudang
     */
    public function index()
    {
        $gudangs = Gudang::latest()->paginate(10);
        return view('pages.admin.gudangs.index', compact('gudangs'));
    }

    /**
     * Form tambah gudang
     */
    public function create()
    {
        return view('pages.admin.gudangs.create');
    }

    /**
     * Simpan gudang baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_gudang' => 'required|string|max:20|unique:gudangs,kode_gudang',
            'nama_gudang' => 'required|string|max:100',
            'alamat' => 'required|string',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'telepon' => 'nullable|string|max:20',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        Gudang::create($request->all());

        return redirect()->route('admin.gudangs.index')->with('success', 'Gudang berhasil ditambahkan!');
    }

    /**
     * Form edit gudang
     */
    public function edit($id)
    {
        $gudang = Gudang::findOrFail($id);
        return view('pages.admin.gudangs.edit', compact('gudang'));
    }

    /**
     * Update data gudang
     */
    public function update(Request $request, $id)
    {
        $gudang = Gudang::findOrFail($id);

        $request->validate([
            'kode_gudang' => 'required|string|max:20|unique:gudangs,kode_gudang,' . $gudang->id,
            'nama_gudang' => 'required|string|max:100',
            'alamat' => 'required|string',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'telepon' => 'nullable|string|max:20',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $gudang->update($request->all());

        return redirect()->route('admin.gudangs.index')->with('success', 'Gudang berhasil diperbarui!');
    }

    /**
     * Hapus gudang
     */
    public function destroy($id)
    {
        $gudang = Gudang::findOrFail($id);
        $gudang->delete();

        return redirect()->route('admin.gudangs.index')->with('success', 'Gudang berhasil dihapus!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new GudangsImport, $request->file('file'));

        return redirect()->route('admin.gudangs.index')->with('success', 'Data gudang berhasil diimpor!');
    }
}

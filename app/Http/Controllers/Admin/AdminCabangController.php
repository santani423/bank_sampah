<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\cabang;
use Illuminate\Http\Request;

class AdminCabangController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $page = max(1, (int) $request->get('page', 1));
        $search = $request->get('name');

        $query = cabang::query();

        if ($search) {
            $query->where('nama', 'like', '%' . $search . '%');
        }

        $cabangs = $query->paginate($perPage, ['*'], 'page', $page);

        return view('pages.admin.cabang.index', compact('cabangs', 'search'));
    }

    public function create()
    {
        return view('pages.admin.cabang.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_cabang'     => 'required|string|max:100',
            'alamat'          => 'required|string',
            'kota'            => 'required|string|max:100',
            'provinsi'        => 'required|string|max:100',
            'kode_pos'        => 'nullable|string|max:10',
            'telepon'         => 'nullable|string|max:20',
            'tanggal_berdiri' => 'nullable|date',
            'status'          => 'required|in:aktif,nonaktif',
        ]);

        // Generate kode_cabang (example: CAB-YYYYMMDDHHMMSS)
        $validated['kode_cabang'] = 'CAB-' . date('YmdHis');

        cabang::create($validated);

        return redirect()->route('admin.cabang.index')->with('success', 'Cabang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $cabang = cabang::findOrFail($id);

        // Convert tanggal_berdiri to Carbon instance if not null
        if ($cabang->tanggal_berdiri) {
            $cabang->tanggal_berdiri = \Carbon\Carbon::parse($cabang->tanggal_berdiri);
        }

        return view('pages.admin.cabang.edit', compact('cabang'));
    }

    public function show($id)
    {
        $cabang = cabang::findOrFail($id);

        // Convert tanggal_berdiri to Carbon instance if not null
        if ($cabang->tanggal_berdiri) {
            $cabang->tanggal_berdiri = \Carbon\Carbon::parse($cabang->tanggal_berdiri);
        }

        return view('pages.admin.cabang.show', compact('cabang'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_cabang'     => 'required|string|max:100',
            'alamat'          => 'required|string',
            'kota'            => 'required|string|max:100',
            'provinsi'        => 'required|string|max:100',
            'kode_pos'        => 'nullable|string|max:10',
            'telepon'         => 'nullable|string|max:20',
            'tanggal_berdiri' => 'nullable|date',
            'status'          => 'required|in:aktif,nonaktif',
        ]);

        $cabang = cabang::findOrFail($id);
        $cabang->update($validated);

        return redirect()->route('admin.cabang.index')->with('success', 'Cabang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $cabang = cabang::findOrFail($id);
        $cabang->delete();

        return redirect()->route('admin.cabang.index')->with('success', 'Cabang berhasil dihapus.');
    }

}

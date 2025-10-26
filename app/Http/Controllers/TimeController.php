<?php

namespace App\Http\Controllers;

use App\Models\Time;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TimeController extends Controller
{
    /**
     * ✅ Tampilkan semua data tim
     */
    public function index()
    {
        $times = Time::latest()->paginate(1000000);
        return view('pages.admin.time.index', compact('times'));
    }

    /**
     * ✅ Form tambah data tim
     */
    public function create()
    {
        return view('pages.admin.time.create');
    }

    /**
     * ✅ Simpan data baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'avatar'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'jabatan'    => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'jabatan', 'keterangan']);

        // Simpan avatar jika ada
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        Time::create($data);

        return redirect()->route('admin.time.index')
            ->with('success', 'Data tim berhasil ditambahkan.');
    }

    /**
     * ✅ Form edit data tim
     */
    public function edit($id)
    {
        $time = Time::findOrFail($id);
        return view('pages.admin.time.edit', compact('time'));
    }

    /**
     * ✅ Update data tim
     */
    public function update(Request $request, $id)
    {
        $time = Time::findOrFail($id);

    
        $request->validate([
            'name'       => 'required|string|max:100',
            'avatar'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'jabatan'    => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'jabatan', 'keterangan']);

        // Jika ada avatar baru, hapus yang lama dan simpan yang baru
        if ($request->hasFile('avatar')) {
            if ($time->avatar && Storage::disk('public')->exists($time->avatar)) {
                Storage::disk('public')->delete($time->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $time->update($data);

        return redirect()->route('admin.time.index')
            ->with('success', 'Data tim berhasil diperbarui.');
    }

    /**
     * ✅ Hapus data tim
     */
    public function destroy($id)
    {
        $time = Time::findOrFail($id);

        // Hapus avatar dari storage jika ada
        if ($time->avatar && Storage::disk('public')->exists($time->avatar)) {
            Storage::disk('public')->delete($time->avatar);
        }

        // Hapus data dari database
        $time->delete();

        return redirect()->route('admin.time.index')
            ->with('success', 'Data tim berhasil dihapus.');
    }
}

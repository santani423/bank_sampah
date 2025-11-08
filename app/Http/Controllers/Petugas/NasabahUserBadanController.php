<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\JenisBadan;
use App\Models\NasabahBadan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class NasabahUserBadanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $nasabahs = NasabahBadan::with('jenisBadan')
            ->when($request->search, function ($query) use ($request) {
                $query->where('nama_badan', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('username', 'like', '%' . $request->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('pages.petugas.nasabah-badan.index', compact('nasabahs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenisBadans = JenisBadan::all();
        return view('pages.petugas.nasabah-badan.create', compact('jenisBadans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_badan_id' => 'required|exists:jenis_badans,id',
            'nama_badan' => 'required|string|max:150',
            'npwp' => 'nullable|string|max:50|unique:nasabah_badan,npwp',
            'nib' => 'nullable|string|max:50|unique:nasabah_badan,nib',
            'email' => [
                'required',
                'email',
                'max:100',
                'unique:nasabah_badan,email',
                'unique:users,email',
            ],
            'username' => [
                'required',
                'string',
                'max:50',
                'unique:nasabah_badan,username',
                'unique:users,username',
            ],
            'password' => 'required|string|min:6',
            'no_telp' => 'nullable|string|max:20',
            'alamat_lengkap' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        // Handle file upload
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/nasabah-badan', $filename);
            $data['foto'] = $filename;
        } else {
            $data['foto'] = 'profil.png';
        }

        // 1. Buat user di tabel users
        $user = \App\Models\User::create([
            'name' => $data['nama_badan'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => $data['password'],
            'role' => 'nasabah_badan',
        ]);

        // 2. Buat nasabah badan
        $nasabahBadan = NasabahBadan::create($data);

        // 3. Simpan relasi ke tabel user_nasabah_badan
        \App\Models\UserNasabahBadan::create([
            'user_id' => $user->id,
            'nasabah_badan_id' => $nasabahBadan->id,
        ]);

        return redirect()
            ->route('petugas.rekanan.index')
            ->with('success', 'Nasabah Badan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(NasabahBadan $nasabahBadan)
    {
        $nasabahBadan->load('jenisBadan');
        return view('pages.petugas.nasabah-badan.show', compact('nasabahBadan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NasabahBadan $nasabahBadan)
    {
        $jenisBadans = JenisBadan::all();
        return view('pages.petugas.nasabah-badan.edit', compact('nasabahBadan', 'jenisBadans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NasabahBadan $nasabahBadan)
    {
        $request->validate([
            'jenis_badan_id' => 'required|exists:jenis_badans,id',
            'nama_badan' => 'required|string|max:150',
            'npwp' => ['nullable', 'string', 'max:50', Rule::unique('nasabah_badan')->ignore($nasabahBadan->id)],
            'nib' => ['nullable', 'string', 'max:50', Rule::unique('nasabah_badan')->ignore($nasabahBadan->id)],
            'email' => ['required', 'email', 'max:100', Rule::unique('nasabah_badan')->ignore($nasabahBadan->id)],
            'username' => ['required', 'string', 'max:50', Rule::unique('nasabah_badan')->ignore($nasabahBadan->id)],
            'password' => 'nullable|string|min:6',
            'no_telp' => 'nullable|string|max:20',
            'alamat_lengkap' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        $data = $request->except('password');
        
        // Update password only if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle file upload
        if ($request->hasFile('foto')) {
            // Delete old file if exists
            if ($nasabahBadan->foto !== 'profil.png') {
                Storage::delete('public/nasabah-badan/' . $nasabahBadan->foto);
            }

            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/nasabah-badan', $filename);
            $data['foto'] = $filename;
        }

        $nasabahBadan->update($data);

        return redirect()
            ->route('petugas.rekanan.index')
            ->with('success', 'Nasabah Badan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NasabahBadan $nasabahBadan)
    {
        // Delete photo if not default
        if ($nasabahBadan->foto !== 'profil.png') {
            Storage::delete('public/nasabah-badan/' . $nasabahBadan->foto);
        }

        $nasabahBadan->delete();

        return redirect()
            ->route('petugas.rekanan.index')
            ->with('success', 'Nasabah Badan berhasil dihapus!');
    }
}

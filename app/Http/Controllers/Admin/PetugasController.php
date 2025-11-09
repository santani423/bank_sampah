<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Petugas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PetugasController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $page = max(1, (int) $request->get('page', 1));
        $petugas = Petugas::paginate($perPage, ['*'], 'page', $page);
        // dd($petugas->hasPages());
        return view('pages.admin.petugas.index', compact('petugas'));
    }

    public function create()
    {
        return view('pages.admin.petugas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:petugas',
            'username' => 'required|string|unique:petugas',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,petugas'
        ]);

        $pss = Hash::make($request->password);

        Petugas::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'username' => $request->username,
            'password' => $pss,
            'role' => $request->role,
        ]);

        DB::table('users')->insert([
            'name' => $request->nama,
            'email' => $request->email,
            'username' => $request->username,
            'password' => $pss,
            'role' => 'petugas',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.petugas.index')->with('success', 'Petugas berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $petugas = Petugas::with('transaksi')->findOrFail($id);
        return view('pages.admin.petugas.show', compact('petugas'));
    }

    public function edit(string $id)
    {
        $petugas = Petugas::findOrFail($id);

        return view('pages.admin.petugas.edit', compact('petugas'));
    }



    public function update(Request $request, $id)
    {
        $petugas = Petugas::findOrFail($id);

        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:petugas,email,' . $petugas->id,
            'username' => 'required|string|unique:petugas,username,' . $petugas->id,
            'role' => 'required|in:admin,petugas',
            'password' => 'nullable|min:6' // password opsional
        ]);

        // Siapkan data yang akan diupdate
        $petugasData = [
            'nama' => $request->nama,
            'email' => $request->email,
            'username' => $request->username,
            'role' => $request->role,
        ];

        $userData = [
            'name' => $request->nama,
            'email' => $request->email,
            'username' => $request->username,
            'role' => $request->role,
        ];

        // Jika password diisi, hash dan tambahkan ke data update
        if (!empty($request->password)) {
            $hashedPassword = Hash::make($request->password);
            $petugasData['password'] = $hashedPassword;
            $userData['password'] = $hashedPassword;
        }

        // Update data pada tabel users
        $user = User::where('email', $petugas->email)->first();
        if ($user) {
            $user->update($userData);
        }

        // Update data pada tabel petugas
        $petugas->update($petugasData);

        return redirect()->route('admin.petugas.index')
            ->with('success', 'Petugas berhasil diperbarui.');
    }


    public function destroy($petugas)
    {

        Petugas::whereId($petugas)->delete();
        return redirect()->route('admin.petugas.index')->with('success', 'Petugas berhasil dihapus.');
    }
}

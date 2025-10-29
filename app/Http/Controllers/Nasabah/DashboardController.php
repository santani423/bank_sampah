<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\cabang;
use App\Models\Nasabah;
use App\Models\Saldo;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\UserNasabah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Can;

class DashboardController extends Controller
{
    public function index()
    {

        $topup = 50;
        $penarikan = 25;
        // Logic for displaying the dashboard
        $userNasabah =  UserNasabah::where('user_id', auth()->id())->first();
        $transaksi = Transaksi::where('nasabah_id', $userNasabah->nasabah_id)
            ->get();

        $saldo  =  Saldo::where('nasabah_id', $userNasabah->nasabah_id)->first();
        if (!$saldo) {
            $saldo   =  new Saldo();
            $saldo->saldo = 0;
            $saldo->nasabah_id = $userNasabah->nasabah_id;
            $saldo->save();
        }
        // Hitung total berat_kg dari semua detail transaksi
        $totalBerat = $transaksi->flatMap(function ($t) {
            return $t->detailTransaksi; // gabungkan semua detail menjadi satu koleksi
        })->sum('berat_kg');


        return view('pages.nasabah.dashboard', compact('saldo', 'topup', 'penarikan', 'userNasabah', 'totalBerat'));
    }

    public function profile()
    {
        // Logic for displaying the profile
        $userNasabah =  UserNasabah::where('user_id', auth()->id())->first();
        $nasabah =  Nasabah::where('id', $userNasabah->nasabah_id)->first();
        $user =  User::where('id', auth()->id())->first();
        return view('pages.nasabah.profile.index', compact('nasabah', 'user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Ambil data nasabah terkait user ini
        $userNasabah = UserNasabah::where('user_id', $user->id)->firstOrFail();
        $nasabah = Nasabah::findOrFail($userNasabah->nasabah_id);

        // Validasi input
        $validated = $request->validate([
            // User
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'username'      => 'required|string|max:50|unique:users,username,' . $user->id,
            'password'      => 'nullable|string|min:6',
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',

            // Nasabah
            'nama_lengkap'  => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir'  => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'no_hp'         => 'required|string|max:20',
            'alamat_lengkap' => 'required|string',
        ]);

        // Update User
        $user->name     = $validated['name'];
        $user->email    = $validated['email'];
        $user->username = $validated['username'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Foto user
        if ($request->hasFile('foto')) {
            if ($user->foto && $user->foto !== 'default.png') {
                Storage::delete('public/foto/' . $user->foto);
            }


            $fileName = $request->file('foto')->store('foto', 'public');

            $user->foto = $fileName;
        }

        $user->save();

        // Update Nasabah
        $nasabah->nama_lengkap   = $validated['nama_lengkap'];
        $nasabah->jenis_kelamin  = $validated['jenis_kelamin'];
        $nasabah->tempat_lahir   = $validated['tempat_lahir'];
        $nasabah->tanggal_lahir  = $validated['tanggal_lahir'];
        $nasabah->no_hp          = $validated['no_hp'];
        $nasabah->alamat_lengkap = $validated['alamat_lengkap'];
        $nasabah->save();

        return redirect()->route('nasabah.profile', $user->id)
            ->with('success', 'Profile berhasil diperbarui.');
    }
}

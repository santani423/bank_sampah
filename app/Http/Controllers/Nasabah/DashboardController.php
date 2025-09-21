<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\cabang;
use App\Models\Saldo;
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
        $saldo  =  Saldo::where('nasabah_id', $userNasabah->nasabah_id)->first();
        if (!$saldo) {
            $saldo   =  new Saldo();
            $saldo->saldo = 0;
            $saldo->nasabah_id = $userNasabah->nasabah_id;
            $saldo->save();
        }
        return view('pages.nasabah.dashboard', compact('saldo', 'topup', 'penarikan', 'userNasabah'));
    }

    public function profile()
    {
        // Logic for displaying the profile
        $nasabah =  UserNasabah::where('user_id', auth()->id())->first();
        $user =  User::where('id', auth()->id())->first();
        // dd($user);
        return view('pages.nasabah.profile.index', compact('nasabah', 'user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validasi input
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'username'  => 'required|string|max:255|unique:users,username,' . $user->id,
            'password'  => 'nullable|string|min:6',
            'foto'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update field biasa
        $user->name     = $validated['name'];
        $user->email    = $validated['email'];
        $user->username = $validated['username'];

        // Update password jika diisi
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Update foto jika ada upload baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama kecuali default
            if ($user->foto && $user->foto != 'default.png') {
                Storage::delete('public/foto/' . $user->foto);
            }

            $fileName = time() . '.' . $request->foto->extension();
            $request->foto->storeAs('public/foto', $fileName);
            $user->foto = $fileName;
        }

        $user->save();

        return redirect()->route('nasabah.profile', $user->id)
            ->with('success', 'Profile berhasil diperbarui.');
    }
}

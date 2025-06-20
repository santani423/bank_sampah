<?php

namespace App\Http\Controllers;

use App\Models\Nasabah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'petugas') {
                return redirect()->route('petugas.dashboard');
            }
        }

        Alert::error('Gagal!', 'Username atau password salah');
        return back();
    }

    public function logout()
    {
        Auth::logout();

        Alert::success('Selamat Tinggal!', 'Anda telah berhasil logout.');

        return redirect()->route('login');
    }

    public function showRegistrationForm()
    {
        return view('pages.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama_lengkap'   => 'required|string',
            'jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir'   => 'required|string',
            'tanggal_lahir'  => 'required|date',
            'no_hp'          => 'required|string',
            'email'          => 'required|email|unique:nasabah,email',
            'username'       => 'required|string|unique:nasabah,username',
            'password'       => 'required|string|confirmed|min:6',
            'alamat_lengkap' => 'required|string',
        ]);

        $data = $request->except(['password', 'password_confirmation']);
        $data['password'] = bcrypt($request->password);



        $data['status'] = 'aktif';

        $nasabah  =    new  Nasabah();
        $nasabah->no_registrasi   = 'REG-' . strtoupper(uniqid());
        $nasabah->nik = '-';
        $nasabah->status = 'aktif';
        $nasabah->fill($data);
        $nasabah->save(); 
     
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }
}

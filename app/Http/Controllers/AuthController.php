<?php

namespace App\Http\Controllers;

use App\Models\cabang;
use App\Models\CabangUser;
use App\Models\Nasabah;
use App\Models\setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        $setting  =  setting::first();
        return view('pages.auth.login',compact('setting'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        // dd($request->all());
        // dd((Auth::attempt(['username' => $request->username, 'password' => $request->password]) ? 'Login Berhasil' : 'Login Gagal'));
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'petugas') {
                return redirect()->route('petugas.dashboard');
            } elseif ($user->role === 'nasabah') {
                return redirect()->route('nasabah.dashboard');
            } else {
                
                return redirect()->route('login')->with('error', 'Role tidak dikenali');
            }
        }

        
        return back()->with('error', 'Username atau password salah.');
    }

    public function logout()
    {
        Auth::logout();

        Alert::success('Selamat Tinggal!', 'Anda telah berhasil logout.');

        return redirect()->route('login');
    }

    public function showRegistrationForm()
    {
        $cabangs = cabang::all();
        $setting  =  setting::first();   
        return view('pages.auth.register', compact('cabangs','setting'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'cabang_id'   => 'required|string',
            'nama_lengkap'   => 'required|string',
            'jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
            // 'tempagital_lahir'  => 'required|date',
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
        // $nasabah->nik = '-';
        $nasabah->status = 'aktif-face';
        $nasabah->fill($data);
        $nasabah->save();

        $user = new User();
        $user->name = $nasabah->nama_lengkap;
        $user->username = $nasabah->username;
        $user->email = $nasabah->email;
        $user->password = bcrypt($request->password);
        $user->role = 'nasabah';
        $user->save();

        $userNasabah = new \App\Models\UserNasabah();
        $userNasabah->user_id = $user->id;
        $userNasabah->nasabah_id = $nasabah->id;
        $userNasabah->save();

        $cabangUser  =  new CabangUser();
        $cabangUser->cabang_id = $request->cabang_id;
        $cabangUser->user_nasabah_id = $userNasabah->id;
        $cabangUser->save();

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }
}

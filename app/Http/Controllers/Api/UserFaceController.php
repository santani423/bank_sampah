<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;

class UserFaceController extends Controller
{
    public function create(Request $request)
    {
        $jumlah = (int) $request->query('jumlah', 1); // default 1 user
        $now = Carbon::now();

        if ($jumlah < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah user harus lebih dari 0',
            ], 400);
        }

        $createdUsers = [];

        for ($i = 1; $i <= $jumlah; $i++) {
            DB::beginTransaction();
            try {
                // Generate nilai unik
                $uniqueId = Str::random(5) . time() . $i;
                $email = $request->code . "nasabahapi{$uniqueId}@example.com";
                $username =  $request->code . "nasabahapiuser{$uniqueId}";
                $no_hp = "0819" . rand(10000000, 99999999); // random 8 digit

                // pastikan email dan username belum ada
                while (User::where('email', $email)->exists() || User::where('username', $username)->exists()) {
                    $uniqueId = Str::random(5) . time() . $i;
                    $email = "nasabahapi{$uniqueId}@example.com";
                    $username = "nasabahapiuser{$uniqueId}";
                }

                // buat user
                $user = User::create([
                    'name' => "Nasabah API $i",
                    'email' => $email,
                    'username' => $username,
                    'password' => Hash::make('12345678'),
                    'role' => 'nasabah',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                // buat no_registrasi yang unik dan terperinci
                $no_registrasi = 'API' . $request->code .  $now->format('YmdHis') . strtoupper(uniqid());;

                // buat NIK unik menggunakan microtime + loop counter
                $micro = microtime(true); // float, misal 1698401234.123456
                $microStr = str_replace('.', '', (string)$micro); // hilangkan titik
                $nik = '3202' . substr($microStr, 0, 12); // ambil 12 digit unik

                // buat nasabah
                $nasabahId = DB::table('nasabah')->insertGetId([
                    'cabang_id' => 1,
                    'no_registrasi' => $no_registrasi,
                    'nik' => $nik,
                    'nama_lengkap' => "Nasabah API $i",
                    'jenis_kelamin' => $i % 2 === 0 ? 'Laki-laki' : 'Perempuan',
                    'tempat_lahir' => "Kota API $i",
                    'tanggal_lahir' => '1995-01-01',
                    'no_hp' => $no_hp,
                    'email' => $email,
                    'username' => $username,
                    'password' => Hash::make('12345678'),
                    'alamat_lengkap' => "Jl. API Contoh $i",
                    'foto' => 'profil.png',
                    'status' => 'aktif',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                // relasi user_nasabahs
                DB::table('user_nasabahs')->insert([
                    'user_id' => $user->id,
                    'nasabah_id' => $nasabahId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                DB::commit();
                $createdUsers[] = $user;
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat membuat user',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Berhasil membuat $jumlah user nasabah baru",
            'data' => $createdUsers,
        ]);
    }
}

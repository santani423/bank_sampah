<?php

namespace Database\Seeders;

use App\Models\CabangUser;
use App\Models\Saldo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserNasabahSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        for ($i = 1; $i <= 200; $i++) {
            // Insert ke tabel users
            $user = User::create([
                'name' => "Nasabah $i",
                'email' => "nasabah$i@example.com",
                'username' => "nasabahuser$i",
                'password' => Hash::make('12345678'),
                'role' => 'nasabah',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Insert ke tabel nasabah
            $nasabahId = DB::table('nasabah')->insertGetId([
                'cabang_id' => 1,
                'no_registrasi' => "REG" . str_pad($i, 3, "0", STR_PAD_LEFT),
                'nik' => '3201' . str_pad($i, 8, '0', STR_PAD_LEFT), // total 12 digit
                'nama_lengkap' => "Nasabah $i",
                'jenis_kelamin' => $i % 2 === 0 ? 'Laki-laki' : 'Perempuan',
                'tempat_lahir' => "Kota $i",
                'tanggal_lahir' => '1990-01-01',
                'no_hp' => "0812345678" . str_pad($i, 2, '0', STR_PAD_LEFT),
                'email' => "nasabah$i@example.com",
                'username' => "nasabahuser$i",
                'password' => Hash::make('12345678'),
                'alamat_lengkap' => "Jl. Contoh $i, Kota $i",
                'foto' => 'profil.png',
                'status' => 'aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Insert ke tabel user_nasabahs
            DB::table('user_nasabahs')->insert([
                'user_id' => $user->id,
                'nasabah_id' => $nasabahId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);


            Saldo::create([
                'nasabah_id' => $nasabahId,
                'saldo' => random_int(100000000, 500000000),
                'tanggal_update' => now(),
            ]);

            CabangUser::create([
                'cabang_id' => random_int(1, 5),
                'user_nasabah_id' => $nasabahId,
            ]);
        }
    }
}

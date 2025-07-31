<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserNasabahSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 20; $i++) {
            $now = Carbon::now();

            // Insert ke tabel users
            $userId = User::create([
                'name' => "Nasabah $i",
                'email' => "nasabah$i@example.com",
                'username' => "nasabahuser$i",
                'password' =>  Hash::make('12345678'),
                'role' => 'nasabah',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // // Insert ke tabel nasabah
            $nasabahId = DB::table('nasabah')->insertGetId([
                'cabang_id' => 1,
                'no_registrasi' => "REG".str_pad($i, 3, "0", STR_PAD_LEFT),
                'nik' => '3201' . str_pad($i, 12, '0', STR_PAD_LEFT),
                'nama_lengkap' => "Nasabah $i",
                'jenis_kelamin' => $i % 2 == 0 ? 'Laki-laki' : 'Perempuan',
                'tempat_lahir' => 'Kota ' . $i,
                'tanggal_lahir' => '1990-01-01',
                'no_hp' => "0812345678" . str_pad($i, 2, '0', STR_PAD_LEFT),
                'email' => "nasabah$i@example.com",
                'username' => "nasabahuser$i",
                'password' =>  Hash::make('12345678'),
                'alamat_lengkap' => "Jl. Contoh $i, Kota $i",
                'foto' => 'profil.png',
                'status' => 'aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // // Insert ke tabel user_nasabahs
            DB::table('user_nasabahs')->insert([
                'user_id' => $userId,
                'nasabah_id' => $nasabahId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // DB::table('petugas')->insert([
            //      'nama' => "Nasabah $i",
            //     'email' => "nasabah$i@example.com",
            //     'username' => "nasabahuser$i",
            //     'password' => Hash::make('12345678'),
            //     'role' => 'nasabah',
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ]);
        }
    }
}

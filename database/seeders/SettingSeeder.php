<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk tabel settings.
     */
    public function run(): void
    {
        DB::table('settings')->insert([
            'nama' => 'Wostin',
            'logo' => 'wostin/files/assets/images/resources/logo.png',
            'favicon' => 'wostin/files/assets/images/resources/favicon.ico',
            'title' => 'Wostin | Aplikasi Bank Sampah Digital',
            'description' => 'Wostin adalah aplikasi pengelolaan bank sampah modern berbasis web.',
            'keywords' => 'bank sampah, wostin, daur ulang, lingkungan, digital',
            'address' => 'Jl. Raya Hijau No. 88, Kota Bersih, Indonesia',
            'phone' => '021-555-8888',
            'email' => 'info@wostin.id',
            'google_map' => 'https://goo.gl/maps/example123',
            'whatsapp' => '6288289445437',
            'instagram' => 'https://instagram.com/wostin.id',
            'twitter' => 'https://twitter.com/wostin_id',
            'youtube' => 'https://youtube.com/@wostin',
            'tiktok' => 'https://tiktok.com/@wostin.id',
            'no_notifikasi' => '6288289445437',
            'min_penarikan' => 10000.00,
            'max_penarikan' => 1000000.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

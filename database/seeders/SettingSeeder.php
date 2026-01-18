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
            'nama' => 'PT. Berkah Mitra Perdana Sukses',
            'logo' => 'assets/img/logo.png',
            'favicon' => 'assets/img/logo.png',
            'title' => 'BMPS | Aplikasi Bank Sampah Digital',
            'description' => 'BMPS adalah aplikasi pengelolaan bank sampah modern berbasis web.',
            'keywords' => 'bank sampah, BMPS, daur ulang, lingkungan, digital',
            'address' => 'Ruko Puri Jaya Blok AA No. 26 RT 002/011, Desa Sukamantri Kecamatan Pasar Kemis Kabupaten Tangerang-Banten',
            'phone' => '021-555-8888',
            'email' => 'info@berkahmitra.id',
            'google_map' => 'https://goo.gl/maps/example123',
            'whatsapp' => '088289445437',
            'instagram' => 'https://instagram.com/BMPS.id',
            'twitter' => 'https://twitter.com/BMPS_id',
            'youtube' => 'https://youtube.com/@BMPS',
            'tiktok' => 'https://tiktok.com/@BMPS.id',
            'no_notifikasi' => '088289445437',
            'min_penarikan' => 10000.00,
            'max_penarikan' => 1000000.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

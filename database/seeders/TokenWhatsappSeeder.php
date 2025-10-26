<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TokenWhatsappSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk tabel token_whatsapp.
     */
    public function run(): void
    {
        DB::table('token_whatsapp')->insert([
            'token_whatsapp' => 'af8f0b52f771c5f7e449ec8105ff96ee', // ganti dengan token asli kamu
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

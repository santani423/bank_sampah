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
            'token_whatsapp' => 'c9c947a55d92639ba2c475c9806dfbe5', // ganti dengan token asli kamu
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

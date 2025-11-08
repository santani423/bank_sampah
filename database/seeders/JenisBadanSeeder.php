<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisBadanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jenis_badan')->insert([
            [
                'nama' => 'PT',
                'keterangan' => 'Perseroan Terbatas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'CV',
                'keterangan' => 'Commanditaire Vennootschap (Persekutuan Komanditer)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Koperasi',
                'keterangan' => 'Badan usaha yang beranggotakan orang atau badan hukum koperasi dengan prinsip gotong royong',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Yayasan',
                'keterangan' => 'Badan hukum nirlaba yang didirikan untuk tujuan sosial, keagamaan, atau kemanusiaan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

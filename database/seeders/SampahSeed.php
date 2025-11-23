<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SampahSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sampah')->insert([
            [
                'nama_sampah' => 'Botol Plastik',
                'harga_per_kg' => 5000.00,
                'harga_lapak' => 4500.00,
                'gambar' => 'botol_plastik.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_sampah' => 'Kertas HVS',
                'harga_per_kg' => 3000.00,
                'harga_lapak' => 2500.00,
                'gambar' => 'kertas_hvs.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_sampah' => 'Kaleng Minuman',
                'harga_per_kg' => 7000.00,
                'harga_lapak' => 6500.00,
                'gambar' => 'kaleng_minuman.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

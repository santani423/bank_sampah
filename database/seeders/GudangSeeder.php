<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GudangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('gudangs')->insert([
            [
                'kode_gudang' => 'GDG-001',
                'nama_gudang' => 'Gudang Pusat Bogor',
                'alamat' => 'Jl. Raya Pajajaran No. 45, Tanah Sareal, Bogor',
                'kota' => 'Bogor',
                'provinsi' => 'Jawa Barat',
                'kode_pos' => '16161',
                'telepon' => '0251-8888888',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_gudang' => 'GDG-002',
                'nama_gudang' => 'Gudang Cabang Depok',
                'alamat' => 'Jl. Margonda Raya No. 22, Pancoran Mas, Depok',
                'kota' => 'Depok',
                'provinsi' => 'Jawa Barat',
                'kode_pos' => '16431',
                'telepon' => '021-7777777',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_gudang' => 'GDG-003',
                'nama_gudang' => 'Gudang Jakarta Selatan',
                'alamat' => 'Jl. Fatmawati No. 88, Cilandak, Jakarta Selatan',
                'kota' => 'Jakarta Selatan',
                'provinsi' => 'DKI Jakarta',
                'kode_pos' => '12430',
                'telepon' => '021-9999999',
                'status' => 'nonaktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

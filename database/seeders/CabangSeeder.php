<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cabangs')->insert([
            [
                'kode_cabang'     => 'CBG-JKT',
                'nama_cabang'     => 'Cabang Jakarta',
                'alamat'          => 'Jl. Sudirman No. 1, Jakarta Pusat',
                'kota'            => 'Jakarta',
                'provinsi'        => 'DKI Jakarta',
                'kode_pos'        => '10220',
                'telepon'         => '0211234567',
                'tanggal_berdiri' => '2015-01-10',
                'status'          => 'aktif',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'kode_cabang'     => 'CBG-SBY',
                'nama_cabang'     => 'Cabang Surabaya',
                'alamat'          => 'Jl. Basuki Rahmat No. 2, Surabaya',
                'kota'            => 'Surabaya',
                'provinsi'        => 'Jawa Timur',
                'kode_pos'        => '60271',
                'telepon'         => '0317654321',
                'tanggal_berdiri' => '2016-03-15',
                'status'          => 'aktif',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'kode_cabang'     => 'CBG-BDG',
                'nama_cabang'     => 'Cabang Bandung',
                'alamat'          => 'Jl. Asia Afrika No. 3, Bandung',
                'kota'            => 'Bandung',
                'provinsi'        => 'Jawa Barat',
                'kode_pos'        => '40111',
                'telepon'         => '0229876543',
                'tanggal_berdiri' => '2017-05-20',
                'status'          => 'aktif',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'kode_cabang'     => 'CBG-SMG',
                'nama_cabang'     => 'Cabang Semarang',
                'alamat'          => 'Jl. Pandanaran No. 4, Semarang',
                'kota'            => 'Semarang',
                'provinsi'        => 'Jawa Tengah',
                'kode_pos'        => '50241',
                'telepon'         => '0241239876',
                'tanggal_berdiri' => '2018-07-25',
                'status'          => 'aktif',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'kode_cabang'     => 'CBG-MKS',
                'nama_cabang'     => 'Cabang Makassar',
                'alamat'          => 'Jl. Pettarani No. 5, Makassar',
                'kota'            => 'Makassar',
                'provinsi'        => 'Sulawesi Selatan',
                'kode_pos'        => '90222',
                'telepon'         => '0411123456',
                'tanggal_berdiri' => '2019-09-30',
                'status'          => 'aktif',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefFilePengirimanPetugasSeeder extends Seeder
{
    /**
     * Jalankan seeder database.
     */
    public function run(): void
    {
        DB::table('ref_file_pengiriman_petugas')->insert([
            [
                'nama_file'  => 'Foto Surat Jalan',
                'deskripsi'  => 'Foto surat jalan resmi yang digunakan dalam pengiriman.',
                'wajib'      => 1,
                'urutan'     => 1,
                'aktif'      => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_file'  => 'Foto Plat Nomor Kendaraan',
                'deskripsi'  => 'Foto plat nomor kendaraan yang digunakan untuk pengiriman.',
                'wajib'      => 1,
                'urutan'     => 2,
                'aktif'      => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_file'  => 'Foto STNK Kendaraan',
                'deskripsi'  => 'Foto dokumen STNK kendaraan pengiriman sebagai bukti legalitas.',
                'wajib'      => 1,
                'urutan'     => 3,
                'aktif'      => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_file'  => 'Foto Mobil (Muatan)',
                'deskripsi'  => 'Foto mobil beserta muatan yang diangkut pada saat pengiriman.',
                'wajib'      => 1,
                'urutan'     => 4,
                'aktif'      => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lapak;
use App\Models\Cabang;

class LapakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil beberapa cabang untuk dijadikan tempat lapak
        $cabangs = Cabang::limit(5)->get();

        if ($cabangs->count() > 0) {
            $lapaks = [
                [
                    'cabang_id' => $cabangs[0]->id,
                    'kode_lapak' => 'LPK001',
                    'nama_lapak' => 'Lapak Sejahtera',
                    'alamat' => 'Jl. Merdeka No. 123',
                    'kota' => 'Jakarta',
                    'provinsi' => 'DKI Jakarta',
                    'kode_pos' => '12345',
                    'no_telepon' => '081234567890',
                    'deskripsi' => 'Lapak sampah terpercaya di Jakarta',
                    'status' => 'aktif',
                ],
                [
                    'cabang_id' => $cabangs[1]->id ?? $cabangs[0]->id,
                    'kode_lapak' => 'LPK002',
                    'nama_lapak' => 'Lapak Bersih',
                    'alamat' => 'Jl. Sudirman No. 456',
                    'kota' => 'Bandung',
                    'provinsi' => 'Jawa Barat',
                    'kode_pos' => '40123',
                    'no_telepon' => '082345678901',
                    'deskripsi' => 'Lapak sampah berkualitas',
                    'status' => 'aktif',
                ],
                [
                    'cabang_id' => $cabangs[2]->id ?? $cabangs[0]->id,
                    'kode_lapak' => 'LPK003',
                    'nama_lapak' => 'Lapak Mandiri',
                    'alamat' => 'Jl. Gatot Subroto No. 789',
                    'kota' => 'Surabaya',
                    'provinsi' => 'Jawa Timur',
                    'kode_pos' => '60123',
                    'no_telepon' => '083456789012',
                    'deskripsi' => 'Melayani pembelian sampah dengan harga terbaik',
                    'status' => 'aktif',
                ],
                [
                    'cabang_id' => $cabangs[3]->id ?? $cabangs[0]->id,
                    'kode_lapak' => 'LPK004',
                    'nama_lapak' => 'Lapak Hijau',
                    'alamat' => 'Jl. Ahmad Yani No. 321',
                    'kota' => 'Semarang',
                    'provinsi' => 'Jawa Tengah',
                    'kode_pos' => '50123',
                    'no_telepon' => '084567890123',
                    'deskripsi' => 'Lapak ramah lingkungan',
                    'status' => 'aktif',
                ],
                [
                    'cabang_id' => $cabangs[4]->id ?? $cabangs[0]->id,
                    'kode_lapak' => 'LPK005',
                    'nama_lapak' => 'Lapak Jaya',
                    'alamat' => 'Jl. Diponegoro No. 654',
                    'kota' => 'Yogyakarta',
                    'provinsi' => 'DI Yogyakarta',
                    'kode_pos' => '55123',
                    'no_telepon' => '085678901234',
                    'deskripsi' => 'Lapak sampah terlengkap',
                    'status' => 'tidak_aktif',
                ],
            ];

            foreach ($lapaks as $lapak) {
                Lapak::create($lapak);
            }
        }
    }
}

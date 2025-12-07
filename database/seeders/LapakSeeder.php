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
        $cabangs = Cabang::all();

        if ($cabangs->count() > 0) {
            $namaLapak = ['Sejahtera', 'Bersih', 'Mandiri', 'Hijau', 'Jaya', 'Makmur', 'Maju', 'Berkah', 'Sentosa', 'Abadi'];
            $jalan = ['Merdeka', 'Sudirman', 'Gatot Subroto', 'Ahmad Yani', 'Diponegoro', 'Hasanuddin', 'Kartini', 'Veteran', 'Pemuda', 'Soekarno Hatta'];
            $kota = ['Jakarta', 'Bandung', 'Surabaya', 'Semarang', 'Yogyakarta', 'Malang', 'Solo', 'Bogor', 'Depok', 'Tangerang'];
            $provinsi = ['DKI Jakarta', 'Jawa Barat', 'Jawa Timur', 'Jawa Tengah', 'DI Yogyakarta', 'Banten'];
            $approvalStatus = ['pending', 'approved', 'rejected'];

            // Generate 50 sample data
            for ($i = 1; $i <= 50; $i++) {
                $kodeLapak = 'LPK' . str_pad($i, 3, '0', STR_PAD_LEFT);
                $randomNama = $namaLapak[array_rand($namaLapak)];
                $randomJalan = $jalan[array_rand($jalan)];
                $randomKota = $kota[array_rand($kota)];
                $randomProvinsi = $provinsi[array_rand($provinsi)];
                $randomCabang = $cabangs->random();
                $randomApproval = $approvalStatus[array_rand($approvalStatus)];

                // Status aktif jika approved, tidak_aktif jika pending atau rejected
                $status = $randomApproval === 'approved' ? 'aktif' : 'tidak_aktif';

                Lapak::create([
                    'cabang_id' => $randomCabang->id,
                    'kode_lapak' => $kodeLapak,
                    'nama_lapak' => 'Lapak ' . $randomNama . ' ' . $i,
                    'alamat' => 'Jl. ' . $randomJalan . ' No. ' . rand(1, 999),
                    'kota' => $randomKota,
                    'provinsi' => $randomProvinsi,
                    'kode_pos' => rand(10000, 99999),
                    'no_telepon' => '08' . rand(1000000000, 9999999999),
                    'deskripsi' => 'Lapak sampah ' . strtolower($randomNama) . ' melayani dengan baik',
                    'status' => $status,
                    'jenis_metode_penarikan_id' => 1,
                    'nama_rekening' =>  $kodeLapak,
                    'nomor_rekening' =>  rand(10000, 99999),
                    'approval_status' => $randomApproval,
                    'approved_by' => $randomApproval !== 'pending' ? 1 : null,
                    'approved_at' => $randomApproval !== 'pending' ? now() : null,
                    'rejection_reason' => $randomApproval === 'rejected' ? 'Lokasi tidak strategis atau dokumen belum lengkap' : null,
                ]);
            }
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KegiatanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kegiatans')->insert([
            [
                'judul' => 'Kegiatan Bersih-Bersih di Cabang Bogor',
                'deskripsi' => 'Kegiatan membersihkan lingkungan sekitar dan mendaur ulang sampah plastik.',
                'foto_kegiatan' => 'assets/img/bg-404.jpeg',
                'video_edukasi' => 'videos/edukasi/plastik-recycle.mp4',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Workshop Edukasi Daur Ulang Cabang Bandung',
                'deskripsi' => 'Workshop dan pelatihan membuat kerajinan dari plastik bekas.',
                'foto_kegiatan' => 'assets/img/bg-404.jpeg',
                'video_edukasi' => 'https://youtu.be/edukasi-daur-ulang',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Kegiatan Bank Sampah di Cabang Surabaya',
                'deskripsi' => 'Pengumpulan botol plastik dari masyarakat sekitar.',
                'foto_kegiatan' => 'assets/img/bg-404.jpeg',
                'video_edukasi' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Pelatihan Kompos di Cabang Depok',
                'deskripsi' => 'Pelatihan membuat kompos dari sampah organik rumah tangga.',
                'foto_kegiatan' => 'assets/img/bg-404.jpeg',
                'video_edukasi' => 'videos/edukasi/kompos.mp4',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Sosialisasi Pengurangan Sampah Plastik di Jakarta',
                'deskripsi' => 'Sosialisasi pentingnya mengurangi penggunaan plastik sekali pakai.',
                'foto_kegiatan' => 'assets/img/bg-404.jpeg',
                'video_edukasi' => 'https://youtu.be/pengurangan-plastik',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Lomba Daur Ulang Sampah di Cabang Bekasi',
                'deskripsi' => 'Lomba membuat karya seni dari sampah daur ulang.',
                'foto_kegiatan' => 'assets/img/bg-404.jpeg',
                'video_edukasi' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Penyuluhan Bank Sampah di Tangerang',
                'deskripsi' => 'Penyuluhan tentang manfaat bank sampah untuk lingkungan.',
                'foto_kegiatan' => 'assets/img/bg-404.jpeg',
                'video_edukasi' => 'videos/edukasi/bank-sampah.mp4',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], 
        ]);
    }
}

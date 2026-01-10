<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JenisMetodePenarikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timestamp = Carbon::now();

        $data = [
            [
                'nama' => 'BCA',
                'keterangan' => 'Transfer ke rekening Bank Central Asia',
                'code' => 'BCA', // Kode standar Xendit untuk Bank BCA
                'base_fee' => 2500,
                'ppn_percent' => 11,
                'total_fee' => 2775,
                'fee_bearer' => 'CUSTOMER',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'nama' => 'DANA',
                'keterangan' => 'Transfer ke akun DANA pengguna',
                'code' => 'DANA', // Kode standar Xendit untuk E-Wallet DANA
                'base_fee' => 2500,
                'ppn_percent' => 11,
                'total_fee' => 2775,
                'fee_bearer' => 'CUSTOMER',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'nama' => 'OVO',
                'keterangan' => 'Pengiriman ke dompet digital OVO',
                'code' => 'OVO', // Kode standar Xendit untuk E-Wallet OVO
                'base_fee' => 2500,
                'ppn_percent' => 11,
                'total_fee' => 2775,
                'fee_bearer' => 'CUSTOMER',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'nama' => 'Gopay',
                'keterangan' => 'Penarikan ke akun Gopay pengguna',
                'code' => 'GOPAY', // Kode standar Xendit untuk E-Wallet GOPAY
                'base_fee' => 2500,
                'ppn_percent' => 11,
                'total_fee' => 2775,
                'fee_bearer' => 'CUSTOMER',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'nama' => 'ShopeePay',
                'keterangan' => 'Penarikan ke akun ShopeePay pengguna',
                'code' => 'SHOPEEPAY', // Tambahan opsi populer
                'base_fee' => 2500,
                'ppn_percent' => 11,
                'total_fee' => 2775,
                'fee_bearer' => 'CUSTOMER',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
        ];

        DB::table('jenis_metode_penarikans')->insert($data);
    }
}
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
                'code' => 'bank_bca',
                'base_fee' => '2500',
                'ppn_percent' => '11',
                'total_fee' => '2775',
                'fee_bearer' => 'CUSTOMER',
                'temp_before_data' => null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'nama' => 'DANA',
                'keterangan' => 'Transfer ke akun DANA pengguna',
                'code' => 'ewallet_dana',
                'base_fee' => '2500',
                'ppn_percent' => '11',
                'total_fee' => '2775',
                'fee_bearer' => 'CUSTOMER',
                'temp_before_data' => null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'nama' => 'OVO',
                'keterangan' => 'Pengiriman ke dompet digital OVO',
                'code' => 'ewallet_ovo',
                'base_fee' => '2500',
                'ppn_percent' => '11',
                'total_fee' => '2775',
                'fee_bearer' => 'CUSTOMER',
                'temp_before_data' => null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'nama' => 'Gopay',
                'keterangan' => 'Penarikan ke akun Gopay pengguna',
                'code' => 'ewallet_gopay',
                'base_fee' => '2500',
                'ppn_percent' => '11',
                'total_fee' => '2775',
                'fee_bearer' => 'CUSTOMER',
                'temp_before_data' => null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
        ];

        DB::table('jenis_metode_penarikans')->insert($data);
    }
}

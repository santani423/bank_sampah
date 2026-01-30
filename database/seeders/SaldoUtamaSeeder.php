<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SaldoUtama;

class SaldoUtamaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SaldoUtama::create([
            'saldo' => 0,
            'keterangan' => 'Saldo awal Bank Sampah  '
        ]);
    }
}

<?php

namespace Database\Seeders\Dev;

use App\Models\DetailPengirimanLapak;
use App\Models\Lapak;
use App\Models\PengirimanLapak;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class KirimSampahLapak extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $lapaks = Lapak::with('transaksiPending')->get();

        foreach ($lapaks as $key => $lapak) {

            // skip jika tidak ada transaksi pending
            if ($lapak->transaksiPending->isEmpty()) {
                continue;
            }

            $pengiriman = PengirimanLapak::create([
                'kode_pengiriman'     => 'KRMPLK' . str_pad(($key * 100)  + 1, 5, '0', STR_PAD_LEFT),
                'tanggal_pengiriman' => $faker->dateTimeBetween('-30 days', 'now'),
                'tanggal_pengiriman'       => $faker->dateTimeBetween('-30 days', 'now'),

                'driver'     => $faker->name,
                'driver_hp'  => $faker->phoneNumber,
                'plat_nomor' => strtoupper($faker->bothify('B #### ???')),

                'foto_muatan'      => 'pengiriman/2025/12/27/1766820625_694f8b11e44f8.png',
                'foto_plat_nomor'  => 'pengiriman/2025/12/27/1766820625_694f8b11e44f8.png',

                'petugas_id' => 3,
                'gudang_id'  => 1,

                'status_pengiriman' => 'dikirim',
            ]);

            /**
             * Jika nanti ingin membuat relasi detail pengiriman,
             * transaksiPending bisa diproses di sini
             */
            foreach ($lapak->transaksiPending as $transaksi) {
                // contoh:
                // DetailPengirimanLapak::create([...]);
                $detail = new DetailPengirimanLapak();
                $detail->pengiriman_lapak_id = $pengiriman->id;
                $detail->petugas_id = 3;
                $detail->transaksi_lapak_id = $transaksi->id;
                $detail->save();
            }
        }
    }
}

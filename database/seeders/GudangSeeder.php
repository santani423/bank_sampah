<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\Gudang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class GudangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $cabangs = Cabang::all();

        foreach ($cabangs as $index => $cabang) {

            $jumlahGudang = rand(1, 10);

            for ($i = 1; $i <= $jumlahGudang; $i++) {

                Gudang::create([
                    'cabang_id'   => $cabang->id,
                    'kode_gudang' => 'CST' . Str::padLeft($cabang->id, 3, '0') . '-' . $i,
                    'nama_gudang' => 'Customer ' . $i . ' ' . $cabang->nama_cabang,

                    // alamat disesuaikan tapi tetap masuk akal
                    'alamat'   => $faker->streetAddress,
                    'kota'     => $cabang->kota,
                    'provinsi' => $cabang->provinsi,
                    'kode_pos' => $faker->postcode,
                    'telepon'  => $faker->phoneNumber,

                    'status' => 'aktif',
                ]);
            }
        }
    }
}

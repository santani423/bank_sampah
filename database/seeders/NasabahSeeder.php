<?php

namespace Database\Seeders;

use App\Models\CabangUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Nasabah;
use App\Models\Saldo;
use Faker\Factory as Faker;

class NasabahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 200; $i++) {
            $nasabah = Nasabah::create([
                'no_registrasi' => 'REG' . $faker->unique()->numberBetween(100, 999),
                'cabang_id' => $faker->randomElement([1, 2, 3]), // Assuming you have 3 cabang
                'nik' => $faker->numerify('################'),
                'nama_lengkap' => $faker->name,
                'jenis_kelamin' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                'tempat_lahir' => $faker->city,
                'tanggal_lahir' => $faker->date('Y-m-d', '2000-01-01'),
                'no_hp' => $faker->phoneNumber,
                'email' => $faker->unique()->safeEmail,
                'username' => $faker->unique()->userName,
                'password' => bcrypt('12345678'),
                'alamat_lengkap' => $faker->address,
                'foto' => 'default.jpg',
            ]);

            Saldo::create([
                'nasabah_id' => $nasabah->id,
                'saldo' => 0,
                'tanggal_update' => now(),
            ]);

            CabangUser::create([
                'cabang_id' => random_int(1, 5),
                'user_nasabah_id' => $nasabah->id,
            ]);
        }
    }
}

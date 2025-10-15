<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class PetugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tambahkan 2 data utama
        DB::table('petugas')->insert([
            [
                'nama' => 'Admin User',
                'email' => 'admin@example.com',
                'username' => 'admin',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Petugas User',
                'email' => 'petugas@example.com',
                'username' => 'petugas',
                'password' => Hash::make('12345678'),
                'role' => 'petugas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'username' => 'admin',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Petugas User',
                'email' => 'petugas@example.com',
                'username' => 'petugas',
                'password' => Hash::make('12345678'),
                'role' => 'petugas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
       
             
            [
                'name' => 'Nasabah User',
                'email' => 'nasabah@example.com',
                'username' => 'nasabah', 
                'password' => Hash::make('12345678'),
                'role' => 'nasabah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Tambahkan 100 data dummy
        $faker = Faker::create();

        for ($i = 1; $i <= 11; $i++) {
            $name = $faker->name;
            $email = $faker->unique()->safeEmail;
            $username = 'petugas' . $i;
            $pss = Hash::make('12345678');
            DB::table('petugas')->insert([
                'nama' => $name,
                'email' => $email,
                'username' => $username,
                'password' => $pss,
                'role' => 'petugas',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('users')->insert([
                'name' => $name,
                'email' => $email,
                'username' => $username,
                'password' => $pss,
                'role' => 'petugas',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
         

         // Ambil semua id petugas
        $petugasIds = DB::table('petugas')->pluck('id')->toArray();

 

        foreach ($petugasIds as $petugasId) {
            DB::table('petugas_cabangs')->insert([
                'cabang_id' => rand(1, 3),
                'petugas_id' => $petugasId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('saldo_petugas')->insert([
                'saldo' => rand(100000000, 3000000000),
                'petugas_id' => $petugasId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);


        }
    }
}

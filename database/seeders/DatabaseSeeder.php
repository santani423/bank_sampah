<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CabangSeeder::class);
        $this->call(PetugasSeeder::class);
        $this->call(SampahSeed::class);
        // $this->call(NasabahSeeder::class);
        $this->call(UserNasabahSeeder::class);
        $this->call(PengepulSeeder::class);
        $this->call(FeedbackSeeder::class);
        $this->call(NewsSeeder::class);
        $this->call(KegiatanSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(JenisMetodePenarikanSeeder::class);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CleanSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');

        for ($i = 1; $i <= 20; $i++) {
            $title = $faker->sentence(3);
            DB::table('cleans')->insert([
                'title' => $title,
                'slug' => Str::slug($title) . '-' . Str::random(5),
                'description' => $faker->paragraph(4),
                'image' => 'uploads/cleans/clean' . $i . '.jpg',
                'status' => $faker->randomElement(['active', 'inactive']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

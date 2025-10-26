<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i = 1; $i <= 50; $i++) {
            $title = $faker->sentence(4);
            
            Activity::create([
                'title' => $title,
                'slug' => Str::slug($title),
                'description' => $faker->paragraph(),
                'content' => '<p>' . implode('</p><p>', $faker->paragraphs(3)) . '</p>',
                'start_date' => $faker->dateTimeBetween('2025-10-01', '2025-12-31')->format('Y-m-d'),
                'end_date' => $faker->dateTimeBetween('2025-10-01', '2026-01-15')->format('Y-m-d'),
                'location' => $faker->city,
                'label_id' => $faker->numberBetween(1, 3), // sesuaikan jumlah label yang kamu miliki
                'image' => 'uploads/activities/1761470310_image214.png',
                'status' => $faker->randomElement(['active', 'inactive']),
            ]);
        }
    }
}

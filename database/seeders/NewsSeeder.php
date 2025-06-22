<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Ambil semua id kategori_news yang ada
        $kategoriIds = DB::table('kategori_news')->pluck('id')->toArray();

        // Jika belum ada kategori, buat dummy kategori
        if (empty($kategoriIds)) {
            $kategoriNames = ['Kebersihan', 'Pengelolaan Sampah'];
            foreach ($kategoriNames as $name) {
            $kategoriIds[] = DB::table('kategori_news')->insertGetId([
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            }
        }

        for ($i = 0; $i < 20; $i++) {
            DB::table('news')->insert([
                'kategori_news_id' => $faker->randomElement($kategoriIds),
                'title' => $faker->sentence,
                'content' => $faker->paragraphs(3, true),
                'thumbnail' => 'wostin\files\assets\images\blog\news-1-1.jpg',
                'author' => $faker->name,
                'published_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'is_published' => $faker->boolean(80),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CleanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'title' => 'Global Institute',
                'description' => null,
                'image' => 'cleans/pB5kQKU5q8gxxnxAQ9xSmJJy35TjkezZCqYgoDNs.png',
                'status' => 'active',
            ],
            [
                'title' => 'ECO FIBER, PT',
                'description' => null,
                'image' => 'cleans/Pt8MjR3Xu13oDGQX8rtkIpt3hq0AnIktplNzedJM.png',
                'status' => 'active',
            ],
            [
                'title' => 'ALAM LESTARI LANCAR, PT',
                'description' => null,
                'image' => 'cleans/T9JyrPt4zEGDVHU5BcCg9S4DKr29Z7uQldyECKvL.webp',
                'status' => 'active',
            ],
            [
                'title' => 'LANGGENG JAYA FIBERINDO, PT',
                'description' => null,
                'image' => 'cleans/RESkAqZyV2iBLNh3H8ZhOj3X7uywczIUzSDJGvdk.png',
                'status' => 'active',
            ],
            [
                'title' => 'GREEN EVEREST, PT',
                'description' => null,
                'image' => 'cleans/ktecN193YM9dl1VVxATgf7Wcg4MuA93NWRCoqj1h.png',
                'status' => 'active',
            ],
            [
                'title' => 'NEW HARVESINDO INTERNATIONAL, PT',
                'description' => null,
                'image' => 'cleans/2IH85vGTlE5LaCXUvWrIVXWnyMe7DSRABJ2jJ4nN.png',
                'status' => 'active',
            ],
            [
                'title' => 'ASIA BOTTLE CYCLING, PT (ABC)',
                'description' => null,
                'image' => 'cleans/GYziDfNFXq1XBQniiUjDwYc5HMyxgv48kuzifouD.png',
                'status' => 'active',
            ],
            [
                'title' => 'HONGSENGKAI PLASTIC, PT',
                'description' => null,
                'image' => 'cleans/K7hM95x5zFZaXO1P6NBUpaN7zyJ4RSAUqirerYSx.png',
                'status' => 'active',
            ],
            [
                'title' => 'Adipisci sint et alias voluptas.',
                'description' => 'Suscipit dolor consequatur explicabo rerum. Quibusdam eaque sed ad eius culpa. Unde aut rerum sunt amet. Modi harum fugit omnis ipsa odio. Dignissimos in aut temporibus ab dolores dolor est. Commodi modi maxime aspernatur sapiente.',
                'image' => 'cleans/JMGlcVu913taC15FM11NXcDO1BWoiExgswMLS82k.jpg',
                'status' => 'active',
            ],
            [
                'title' => 'Iste sit est sint.',
                'description' => 'Neque dolor mollitia aut laboriosam aut. Quod ducimus velit repellendus. Architecto rerum et est aut. Provident accusamus voluptatem et qui impedit at.',
                'image' => 'cleans/JMGlcVu913taC15FM11NXcDO1BWoiExgswMLS82k.jpg',
                'status' => 'inactive',
            ],
        ];

        foreach ($data as $item) {
            DB::table('cleans')->insert([
                'title' => $item['title'],
                'slug' => Str::slug($item['title']),
                'description' => $item['description'],
                'image' => $item['image'], // PATH RELATIF
                'status' => $item['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('times')->insert([
            [
                'name' => 'ANISA SINDY NURHIDAYATI',
                'avatar' => 'avatars/wlNLJKxbdmMMSlevo5TpwUyrWVP17ZRMcqcef7VM.png',
                'jabatan' => null,
                'keterangan' => null,
                'temp_before_data' => null,
                'created_at' => '2025-11-01 04:18:39',
                'updated_at' => '2025-11-01 04:18:39',
            ],
            [
                'name' => 'RIO ANDRI ASTIKO',
                'avatar' => 'avatars/k0VlMOJFp8u5eQd6jVBwuUaEvHhMdLtjS4tf6bDP.png',
                'jabatan' => null,
                'keterangan' => null,
                'temp_before_data' => null,
                'created_at' => '2025-11-01 04:19:29',
                'updated_at' => '2025-11-01 04:19:29',
            ],
            [
                'name' => 'MUHAMAD MARTIN',
                'avatar' => 'avatars/b2jBqhGv9XGfrruCuzHr5MOZCMhiYHfr45LZTOwP.png',
                'jabatan' => null,
                'keterangan' => null,
                'temp_before_data' => null,
                'created_at' => '2025-11-01 04:20:14',
                'updated_at' => '2025-11-01 04:20:14',
            ],
            [
                'name' => 'ANAS HANURAWAN',
                'avatar' => 'avatars/kYGLOWBKdcZTtNJTQdJijxYoIilfeo7vAtEz66we.png',
                'jabatan' => null,
                'keterangan' => null,
                'temp_before_data' => null,
                'created_at' => '2025-11-01 04:20:54',
                'updated_at' => '2025-11-01 04:20:54',
            ],
        ]);
    }
}

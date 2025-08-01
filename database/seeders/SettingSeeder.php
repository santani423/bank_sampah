<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insert([
            'nama' => 'Wostin',
            'logo' => 'wostin/files/assets/images/resources/logo.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        
    }
}

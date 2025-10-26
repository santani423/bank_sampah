<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Label;

class LabelSeeder extends Seeder
{
    public function run(): void
    {
        Label::insert([
            ['name' => 'Important', 'slug' => 'important', 'color' => '#ff0000', 'description' => 'High priority label'],
            ['name' => 'Info', 'slug' => 'info', 'color' => '#007bff', 'description' => 'General information label'],
            ['name' => 'Success', 'slug' => 'success', 'color' => '#28a745', 'description' => 'Indicates success or completion'],
        ]);
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('labels', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama label
            $table->string('slug')->unique(); // Slug unik (misal: untuk URL-friendly)
            $table->string('color')->nullable(); // Warna label (opsional, misal: #ff0000)
            $table->text('description')->nullable(); // Deskripsi label
            $table->longText('temp_before_data')->nullable();  
            $table->timestamps();
        });
    }

    /**
     * Rollback migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('labels');
    }
};

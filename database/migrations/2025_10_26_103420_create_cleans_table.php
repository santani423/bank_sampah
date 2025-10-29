<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCleansTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cleans', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // judul kegiatan
            $table->string('slug')->unique(); // slug untuk URL
            $table->text('description')->nullable(); // deskripsi singkat    
            $table->string('image')->nullable(); // gambar utama kegiatan
            $table->enum('status', ['active', 'inactive'])->default('active'); // status kegiatan 
            $table->longText('temp_before_data')->nullable();  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cleans');
    }
}

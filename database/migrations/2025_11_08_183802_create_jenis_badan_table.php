<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jenis_badan', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->unique(); // Contoh: PT, CV, Koperasi, Yayasan
            $table->text('keterangan')->nullable();
            $table->longText('temp_before_data')->nullable();  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_badan');
    }
};

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
        Schema::create('jenis_metode_penarikans', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable(); 
            $table->string('keterangan')->nullable(); 
            $table->string('code')->nullable(); 
            $table->longText('temp_before_data')->nullable();  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_metode_penarikans');
    }
};

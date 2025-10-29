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
        Schema::create('ref_file_pengiriman_petugas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_file', 255);
            $table->text('deskripsi')->nullable();
            $table->boolean('wajib')->default(0); // 1 = wajib upload, 0 = opsional
            $table->integer('urutan')->default(0);
            $table->longText('temp_before_data')->nullable();  
            $table->boolean('aktif')->default(1); // 1 = aktif, 0 = nonaktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_file_pengiriman_petugas');
    }
};

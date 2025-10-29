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
        Schema::create('pengiriman_petugas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengiriman')->unique();
            $table->date('tanggal_pengiriman')->nullable();
            $table->unsignedBigInteger('petugas_id');
            $table->longText('temp_before_data')->nullable();  
            $table->unsignedBigInteger('gudang_id')->nullable();
            $table->enum('status_pengiriman', ['draft', 'dikirim', 'diterima', 'batal'])->default('draft');
            $table->timestamps();

            // Relasi ke tabel petugas
            $table->foreign('petugas_id')->references('id')->on('petugas')->onDelete('cascade');

            // Relasi opsional ke tabel gudang
            $table->foreign('gudang_id')->references('id')->on('gudangs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengiriman_petugas');
    }
};

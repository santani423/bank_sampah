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
        Schema::create('pengiriman_lapaks', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengiriman')->unique();
            $table->unsignedBigInteger('gudang_id')->nullable();
            $table->unsignedBigInteger('lapak_id')->nullable();
            $table->date('tanggal_pengiriman')->nullable();
            $table->string('driver')->nullable();
            $table->string('driver_hp')->nullable();
            $table->string('plat_nomor')->nullable();
            $table->string('foto_muatan')->nullable();
            $table->string('foto_plat_nomor')->nullable(); 
            $table->string('foto_penerimaan')->nullable(); 
            $table->string('catatan')->nullable(); 
            $table->unsignedBigInteger('petugas_id');
            $table->longText('temp_before_data')->nullable();
            $table->enum('status_pengiriman', ['draft', 'dikirim', 'diterima', 'batal','pending'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengiriman_lapaks');
    }
};

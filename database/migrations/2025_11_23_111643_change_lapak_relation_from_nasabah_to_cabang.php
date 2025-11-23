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
        // Drop dan create ulang tabel lapak dengan relasi ke cabang
        Schema::dropIfExists('lapak');
        
        Schema::create('lapak', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cabang_id');
            $table->string('kode_lapak', 50)->unique();
            $table->string('nama_lapak', 100);
            $table->text('alamat');
            $table->string('kota', 50)->nullable();
            $table->string('provinsi', 50)->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('foto', 100)->nullable();
            $table->enum('status', ['aktif', 'tidak_aktif'])->default('aktif');
            $table->timestamps();

            // Foreign key
            $table->foreign('cabang_id')->references('id')->on('cabangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop dan create ulang tabel lapak dengan relasi ke nasabah (versi lama)
        Schema::dropIfExists('lapak');
        
        Schema::create('lapak', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nasabah_id');
            $table->string('kode_lapak', 50)->unique();
            $table->string('nama_lapak', 100);
            $table->text('alamat');
            $table->string('kota', 50)->nullable();
            $table->string('provinsi', 50)->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('foto', 100)->nullable();
            $table->enum('status', ['aktif', 'tidak_aktif'])->default('aktif');
            $table->timestamps();

            // Foreign key
            $table->foreign('nasabah_id')->references('id')->on('nasabah')->onDelete('cascade');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nasabah_badan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_badan_id')->constrained('jenis_badan')->onDelete('cascade');
            $table->string('nama_badan', 150);
            $table->string('npwp', 50)->nullable()->unique();
            $table->string('nib', 50)->nullable()->unique()->comment('Nomor Induk Berusaha');
            $table->string('email', 100)->unique();
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->string('no_telp', 20)->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->string('foto', 100)->default('profil.png');
            $table->enum('status', ['aktif', 'tidak_aktif'])->default('aktif');
            $table->longText('temp_before_data')->nullable()->comment('menyimpan data sebelum update untuk audit log');
            $table->timestamps();

            // Optional index for optimization
            $table->index(['jenis_badan_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nasabah_badan');
    }
};

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
        Schema::create('user_nasabah_badan', function (Blueprint $table) {
            $table->id();
            // Relasi ke users
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // Relasi ke nasabah_badan
            $table->foreignId('nasabah_badan_id')
                ->constrained('nasabah_badan')
                ->onDelete('cascade');
            $table->longText('temp_before_data')->nullable()->comment('menyimpan data sebelum update untuk audit log');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_nasabah_badan');
    }
};

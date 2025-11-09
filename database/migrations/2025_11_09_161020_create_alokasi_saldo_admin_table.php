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
        Schema::create('alokasi_saldo_admin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('petugas')->onDelete('cascade'); // admin yang melakukan alokasi
            $table->foreignId('petugas_id')->constrained('petugas')->onDelete('cascade'); // petugas penerima
            $table->bigInteger('nominal'); // nominal yang dialokasikan
            $table->bigInteger('saldo_admin_sebelum'); // saldo admin sebelum alokasi
            $table->bigInteger('saldo_admin_sesudah'); // saldo admin sesudah alokasi
            $table->bigInteger('saldo_petugas_sebelum'); // saldo petugas sebelum alokasi
            $table->bigInteger('saldo_petugas_sesudah'); // saldo petugas sesudah alokasi
            $table->text('keterangan')->nullable(); // keterangan alokasi
            $table->longText('temp_before_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alokasi_saldo_admin');
    }
};

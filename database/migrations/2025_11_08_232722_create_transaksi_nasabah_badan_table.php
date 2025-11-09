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
        Schema::create('transaksi_nasabah_badan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi', 100)->unique();
            $table->foreignId('nasabah_badan_id')->constrained('nasabah_badan')->onDelete('cascade');
            $table->foreignId('petugas_id')->nullable()->constrained('petugas')->onDelete('set null');
            $table->date('tanggal_transaksi');
            $table->decimal('total_transaksi', 15, 2)->default(0);
            $table->enum('status', ['pending', 'selesai', 'dibatalkan'])->default('selesai');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_nasabah_badan');
    }
};

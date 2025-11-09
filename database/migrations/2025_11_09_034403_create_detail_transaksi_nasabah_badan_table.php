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
        Schema::create('detail_transaksi_nasabah_badan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaksi_nasabah_badan_id');
            $table->foreignId('sampah_id')->constrained('sampah')->onDelete('cascade');
            $table->decimal('berat_kg', 10, 2);
            $table->decimal('harga_per_kg', 15, 2);
            $table->decimal('harga_total', 15, 2);
            $table->longText('temp_before_data')->nullable();  
            $table->timestamps();
            
            // Custom foreign key name untuk menghindari nama yang terlalu panjang
            $table->foreign('transaksi_nasabah_badan_id', 'fk_detail_trans_nb_trans_nb_id')
                  ->references('id')->on('transaksi_nasabah_badan')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi_nasabah_badan');
    }
};

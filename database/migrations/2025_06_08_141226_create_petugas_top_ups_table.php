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
        Schema::create('petugas_top_ups', function (Blueprint $table) {
            $table->id();
             // Relasi ke tabel users
            $table->foreignId('petugas_id')->constrained()->onDelete('cascade');

            // Informasi transaksi
            $table->string('order_id')->unique(); // ID unik dari Midtrans
            $table->bigInteger('amount');          // Nominal topup
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->string('payment_type')->nullable();   // credit_card, bank_transfer, dll
            $table->string('transaction_id')->nullable(); // ID dari Midtrans

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petugas_top_ups');
    }
};

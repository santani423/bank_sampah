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
        Schema::create('petugas_balace_mutations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('petugas_id')->constrained()->onDelete('cascade');
               // Informasi mutasi saldo
            $table->bigInteger('amount'); // nominal perubahan (bisa positif atau negatif)
            $table->enum('type', ['credit', 'debit']); // 	credit (menambah saldo) atau debit (mengurangi saldo)
            $table->string('source')->nullable(); // sumber: topup, refund, order, adjustment, etc
            $table->text('description')->nullable(); // keterangan tambahan
            $table->longText('temp_before_data')->nullable();  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petugas_balace_mutations');
    }
};

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
        Schema::create('saldo_petugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('petugas_id')->constrained()->onDelete('cascade'); 
            $table->longText('temp_before_data ');  
            $table->bigInteger('saldo');  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saldo_petugas');
    }
};

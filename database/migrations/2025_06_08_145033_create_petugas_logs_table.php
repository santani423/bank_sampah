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
        Schema::create('petugas_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('petugas_id')->constrained()->onDelete('cascade');
            $table->string('activity'); // contoh: login, topup, logout, dll
            $table->string('ip_address')->nullable(); 
            $table->string('user_agent')->nullable();
            $table->text('description')->nullable();
            $table->longText('temp_before_data')->nullable();  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petugas_logs');
    }
};

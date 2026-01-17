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
        Schema::create('otps', function (Blueprint $table) {
            $table->id();

            // Identitas penerima OTP
            $table->string('identifier');
            // contoh: no_hp / email

            // OTP (disarankan hash)
            $table->string('otp_hash');

            // Tujuan OTP
            $table->string('type')->default('verification');
            // contoh: register, login, reset_password

            // Status
            $table->boolean('is_used')->default(false);

            // Expired
            $table->timestamp('expired_at')->nullable();

            $table->timestamps();

            // Index untuk performa
            $table->index(['identifier', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};

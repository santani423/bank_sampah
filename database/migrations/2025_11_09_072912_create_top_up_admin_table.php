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
        Schema::create('top_up_admin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Admin yang melakukan top up
            $table->decimal('jumlah', 15, 2);
            $table->string('metode_pembayaran')->default('xendit'); // xendit
            $table->string('status')->default('pending'); // pending, success, failed, expired
            $table->string('xendit_invoice_id')->nullable();
            $table->string('xendit_invoice_url')->nullable();
            $table->string('xendit_external_id')->nullable();
            $table->text('keterangan')->nullable();
            $table->longText('temp_before_data')->nullable();  
            $table->timestamp('tanggal_bayar')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('top_up_admin');
    }
};

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
        Schema::table('transaksi', function (Blueprint $table) {
            // Make nasabah_id nullable
            $table->foreignId('nasabah_id')->nullable()->change();
            
            // Add nasabah_badan_id column
            $table->foreignId('nasabah_badan_id')->nullable()->after('nasabah_id')->constrained('nasabah_badan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropForeign(['nasabah_badan_id']);
            $table->dropColumn('nasabah_badan_id');
            
            // Revert nasabah_id to not nullable
            $table->foreignId('nasabah_id')->nullable(false)->change();
        });
    }
};

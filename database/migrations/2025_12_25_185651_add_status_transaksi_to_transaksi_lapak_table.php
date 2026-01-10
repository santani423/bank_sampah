<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transaksi_lapak', function (Blueprint $table) {
            $table->enum('status_transaksi', [
                'pending',
                'dikirim',
                'approved',
                'rejected'
            ])->default('pending')->after('approval');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_lapak', function (Blueprint $table) {
            $table->dropColumn('status_transaksi');
        });
    }
};

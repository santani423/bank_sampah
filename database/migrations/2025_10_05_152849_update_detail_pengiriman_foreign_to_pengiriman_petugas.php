<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_pengiriman', function (Blueprint $table) {
            // 1️⃣ Hapus foreign key lama
            $table->dropForeign(['pengiriman_id']);

            // 2️⃣ Tambahkan foreign key baru ke tabel pengiriman_petugas
            $table->foreign('pengiriman_id')
                  ->references('id')
                  ->on('pengiriman_petugas')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('detail_pengiriman', function (Blueprint $table) {
            // Kembalikan ke relasi sebelumnya jika di-rollback
            $table->dropForeign(['pengiriman_id']);

            $table->foreign('pengiriman_id')
                  ->references('id')
                  ->on('pengiriman_pengepul')
                  ->onDelete('cascade');
        });
    }
};

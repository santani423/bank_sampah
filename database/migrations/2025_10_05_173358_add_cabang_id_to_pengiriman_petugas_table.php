<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengiriman_petugas', function (Blueprint $table) {
            $table->unsignedBigInteger('cabang_id')->nullable()->after('id');

            // Jika ada relasi ke tabel cabang, tambahkan foreign key
            $table->foreign('cabang_id')->references('id')->on('cabangs')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pengiriman_petugas', function (Blueprint $table) {
            $table->dropForeign(['cabang_id']);
            $table->dropColumn('cabang_id');
        });
    }
};

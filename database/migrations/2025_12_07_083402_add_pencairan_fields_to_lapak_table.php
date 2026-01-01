<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lapak', function (Blueprint $table) {

            // Tambah foreign key untuk jenis metode penarikan
            $table->unsignedBigInteger('jenis_metode_penarikan_id')
                ->nullable()
                ->after('status');

            $table->string('nama_rekening', 100)
                ->nullable()
                ->after('jenis_metode_penarikan_id');

            $table->string('nomor_rekening', 50)
                ->nullable()
                ->after('nama_rekening');
            $table->string('nama_bank', 50)
                ->nullable()
                ->after('nomor_rekening');

            // Relasi foreign key
            $table->foreign('jenis_metode_penarikan_id')
                ->references('id')
                ->on('jenis_metode_penarikans')
                ->cascadeOnDelete()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('lapak', function (Blueprint $table) {

            // Drop foreign key sebelum drop column
            $table->dropForeign(['jenis_metode_penarikan_id']);
            $table->dropColumn(['jenis_metode_penarikan_id', 'nama_rekening', 'nomor_rekening']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_transaksi_lapak', function (Blueprint $table) {

            // Berat hasil update (akan diisi lewat Model)
            $table->decimal('berat_terupdate', 10, 2)
                ->after('berat_kg')
                ->nullable()
                ->comment('Berat hasil penimbangan ulang');

            // Selisih default 0
            $table->decimal('selisih', 10, 2)
                ->after('berat_terupdate')
                ->default(0)
                ->comment('Selisih berat (susut)');
        });
    }

    public function down(): void
    {
        Schema::table('detail_transaksi_lapak', function (Blueprint $table) {
            $table->dropColumn(['berat_terupdate', 'selisih']);
        });
    }
};

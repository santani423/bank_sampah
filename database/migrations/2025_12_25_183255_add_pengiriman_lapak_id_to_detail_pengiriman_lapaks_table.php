<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('detail_pengiriman_lapaks', function (Blueprint $table) {
            $table->unsignedBigInteger('pengiriman_lapak_id')
                  ->after('id');

            $table->foreign('pengiriman_lapak_id')
                  ->references('id')
                  ->on('pengiriman_lapaks')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('detail_pengiriman_lapaks', function (Blueprint $table) {
            $table->dropForeign(['pengiriman_lapak_id']);
            $table->dropColumn('pengiriman_lapak_id');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan perubahan schema.
     */
    public function up(): void
    {
        Schema::table('pengiriman_petugas', function (Blueprint $table) {
            $table->text('catatan')->nullable()->after('status_pengiriman')
                  ->comment('Catatan tambahan terkait pengiriman');
        });
    }

    /**
     * Kembalikan perubahan schema.
     */
    public function down(): void
    {
        Schema::table('pengiriman_petugas', function (Blueprint $table) {
            $table->dropColumn('catatan');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_pengiriman', function (Blueprint $table) {
            $table->decimal('harga_per_kg', 10, 2)->nullable()->change();
            $table->decimal('harga_total', 15, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('detail_pengiriman', function (Blueprint $table) {
            $table->decimal('harga_per_kg', 10, 2)->nullable(false)->change();
            $table->decimal('harga_total', 15, 2)->nullable(false)->change();
        });
    }
};

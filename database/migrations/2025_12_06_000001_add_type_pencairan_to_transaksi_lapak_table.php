<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('transaksi_lapak', function (Blueprint $table) {
            $table->enum('type_pencairan', ['TRANSFER', 'SALDO'])->nullable()->after('approval');
        });
    }

    public function down()
    {
        Schema::table('transaksi_lapak', function (Blueprint $table) {
            $table->dropColumn('type_pencairan');
        });
    }
};

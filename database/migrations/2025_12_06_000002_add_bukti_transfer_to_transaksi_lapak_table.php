<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('transaksi_lapak', function (Blueprint $table) {
            $table->string('bukti_transfer')->nullable()->after('type_pencairan');
        });
    }

    public function down()
    {
        Schema::table('transaksi_lapak', function (Blueprint $table) {
            $table->dropColumn('bukti_transfer');
        });
    }
};

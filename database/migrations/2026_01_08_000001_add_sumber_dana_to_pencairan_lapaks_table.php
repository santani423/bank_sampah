<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSumberDanaToPencairanLapaksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pencairan_lapaks', function (Blueprint $table) {
            $table->enum('sumber_dana', ['saldo_admin', 'transfer_admin'])
                ->after('fee_bearer')
                ->default('saldo_admin')
                ->comment('Sumber dana pencairan: saldo admin atau transfer admin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pencairan_lapaks', function (Blueprint $table) {
            $table->dropColumn('sumber_dana');
        });
    }
}

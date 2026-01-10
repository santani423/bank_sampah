<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('transaksi_lapak', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lapak_id');
            $table->string('kode_transaksi')->unique();
            $table->date('tanggal_transaksi');
            $table->decimal('total_transaksi', 15, 2);
            $table->enum('approval', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('petugas_id');
            $table->timestamps();

            $table->foreign('lapak_id')->references('id')->on('lapak')->onDelete('cascade');
            $table->foreign('petugas_id')->references('id')->on('petugas')->onDelete('cascade');
        });

        Schema::create('detail_transaksi_lapak', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaksi_lapak_id');
            $table->unsignedBigInteger('sampah_id');
            $table->decimal('berat_kg', 10, 2);
            $table->decimal('harga_per_kg', 15, 2);
            $table->decimal('total_harga', 15, 2);
            $table->timestamps();

            $table->foreign('transaksi_lapak_id')->references('id')->on('transaksi_lapak')->onDelete('cascade');
            $table->foreign('sampah_id')->references('id')->on('sampah')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_transaksi_lapak');
        Schema::dropIfExists('transaksi_lapak');
    }
};

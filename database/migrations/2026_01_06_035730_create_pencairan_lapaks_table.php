<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pencairan_lapaks', function (Blueprint $table) {
            // Primary key
            $table->id();
            $table->string('kode_pencairan')->nullable();
            // Relasi ke tabel lapak (pihak yang mengajukan pencairan)
            $table->foreignId('lapak_id')
                ->constrained('lapak')
                ->onDelete('cascade');

            $table->foreignId('pengiriman_lapak_id')
                ->constrained('pengiriman_lapaks')
                ->onDelete('cascade');

            // Relasi ke tabel metode pencairan (transfer, e-wallet, dsb)
            $table->foreignId('metode_id')
                ->constrained('metode_pencairan')
                ->onDelete('cascade');

            // Jumlah dana yang dicairkan (nilai bruto sebelum potongan)
            $table->decimal('jumlah_pencairan', 15, 2);

            // Tanggal saat lapak mengajukan pencairan dana
            $table->timestamp('tanggal_pengajuan')->nullable();

            // Tanggal saat pencairan diproses / dieksekusi
            $table->timestamp('tanggal_proses')->nullable();

            // Persentase PPN yang dikenakan (contoh: 11%)
            $table->string('ppn_percent')->nullable();

            // Fee kotor (gross fee) sebelum pajak dan potongan lain
            $table->string('fee_gross')->nullable();

            // Jumlah dana yang dicairkan (nilai bruto sebelum potongan)
            $table->decimal('total_pencairan', 15, 2);
            // Fee bersih (gross fee) setelah pajak dan potongan lain
            $table->string('fee_net')->nullable();

            // Penanggung fee:
            // COMPANY  = fee ditanggung perusahaan
            // CUSTOMER = fee dibebankan ke lapak
            $table->enum('fee_bearer', ['COMPANY', 'CUSTOMER']);

            // Status pencairan:
            // pending   = menunggu persetujuan
            // disetujui = disetujui dan siap/proses pencairan
            // ditolak   = pengajuan ditolak
            $table->enum('status', ['pending', 'disetujui', 'ditolak']);

            // Catatan tambahan (alasan penolakan, keterangan admin, dsb)
            $table->text('keterangan')->nullable();

            // created_at dan updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pencairan_lapaks');
    }
};

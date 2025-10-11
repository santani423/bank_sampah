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
        Schema::create('file_pengiriman_petugas', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel pengiriman_petugas
            $table->foreignId('pengiriman_petugas_id')
                  ->constrained('pengiriman_petugas')
                  ->onDelete('cascade');
            
            // Relasi ke tabel referensi file
            $table->foreignId('ref_file_id')
                  ->constrained('ref_file_pengiriman_petugas')
                  ->onDelete('cascade');

            $table->string('nama_file', 255); // nama file sebenarnya
            $table->string('path_file', 500); // lokasi penyimpanan file (storage path)
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamp('uploaded_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_pengiriman_petugas');
    }
};

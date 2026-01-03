<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Jalankan migration.
     *
     * Menambahkan status baru:
     * - selesai : pengiriman telah selesai secara operasional
     * - dibayar : pengiriman telah selesai dan pembayaran telah dilakukan
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE pengiriman_lapaks
            MODIFY status_pengiriman 
            ENUM(
                'draft',
                'pending',
                'dikirim',
                'diterima',
                'selesai',
                'dibayar',
                'batal'
            )
            NOT NULL
            DEFAULT 'pending'
        ");
    }

    /**
     * Rollback migration.
     *
     * Mengembalikan ENUM ke kondisi sebelum ditambahkan
     * status 'selesai' dan 'dibayar'
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE pengiriman_lapaks
            MODIFY status_pengiriman 
            ENUM(
                'draft',
                'pending',
                'dikirim',
                'diterima',
                'batal'
            )
            NOT NULL
            DEFAULT 'pending'
        ");
    }
};

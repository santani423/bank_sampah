<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('logging', function (Blueprint $table) {
            $table->id();

            // Kode unik log: LOG-00001, LOG-00002, dst
            $table->string('code')->unique();

            // Relasi user (opsional)
            $table->unsignedBigInteger('user_id')->nullable();

            // Aksi yang dilakukan (CREATE, UPDATE, DELETE, LOGIN, dll)
            $table->string('action');

            // Deskripsi singkat aktivitas
            $table->text('description')->nullable();

            // DATA JSON → gunakan LONGTEXT agar kompatibel MariaDB
            $table->longText('data_before')->nullable();
            $table->longText('data_after')->nullable();

            // Metadata request
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();

            // Backup tambahan (opsional / debugging)
            $table->longText('temp_before_data')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logging');
    }
};

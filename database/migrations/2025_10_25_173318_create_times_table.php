<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('times', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('avatar')->nullable(); // path atau URL foto
            $table->string('jabatan', 100)->nullable(); // jabatan / posisi
            $table->text('keterangan')->nullable(); // deskripsi tambahan
            $table->longText('temp_before_data')->nullable();  
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('times');
    }
};

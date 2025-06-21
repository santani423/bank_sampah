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
        Schema::create('news', function (Blueprint $table) {
             $table->id();
            $table->string('title'); // Judul berita
            $table->text('content'); // Isi berita
            $table->string('thumbnail')->nullable(); // Gambar thumbnail (opsional)
            $table->string('author')->nullable(); // Penulis
            // $table->enum('category', ['lingkungan', 'kegiatan', 'pengumuman'])->default('lingkungan');
            $table->timestamp('published_at')->nullable(); // Tanggal terbit
            $table->boolean('is_published')->default(false); // Status tampil/tidak
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};

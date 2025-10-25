<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('favicon', 255)->nullable()->after('logo');
            $table->string('title', 255)->nullable()->after('favicon');
            $table->string('description', 255)->nullable()->after('title');
            $table->string('keywords', 255)->nullable()->after('description');
            $table->string('address', 255)->nullable()->after('keywords');
            $table->string('phone', 255)->nullable()->after('address');
            $table->string('email', 255)->nullable()->after('phone');
            $table->string('google_map', 255)->nullable()->after('email');
            $table->string('whatsapp', 255)->nullable()->after('google_map');
            $table->string('instagram', 255)->nullable()->after('whatsapp');
            $table->string('twitter', 255)->nullable()->after('instagram');
            $table->string('youtube', 255)->nullable()->after('twitter');
            $table->string('tiktok', 255)->nullable()->after('youtube');
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'favicon',
                'title',
                'description',
                'keywords',
                'address',
                'phone',
                'email',
                'google_map',
                'whatsapp',
                'instagram',
                'twitter',
                'youtube',
                'tiktok',
            ]);
        });
    }
};

<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Xendit API Key
    |--------------------------------------------------------------------------
    |
    | Masukkan API Key dari Xendit. Disarankan agar disimpan di file .env
    | untuk alasan keamanan. Jangan langsung menulis kunci API di sini.
    |
    */

    'api_key' => env('XENDIT_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Xendit Webhook Secret (Opsional)
    |--------------------------------------------------------------------------
    |
    | Jika Anda menggunakan webhook dan ingin memverifikasi keaslian request
    | dari Xendit, Anda bisa menambahkan webhook secret di sini dan di file .env.
    |
    */

    // 'webhook_secret' => env('XENDIT_WEBHOOK_SECRET', ''),

];

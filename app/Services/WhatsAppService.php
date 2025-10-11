<?php

namespace App\Services;

use App\Models\TokenWhatsApp;
use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    protected $baseUrl;
    protected $deviceId;

    public function __construct()
    {
        // URL endpoint WHCenter
        $this->baseUrl = 'https://app.whacenter.com/api/send';
        $wa = TokenWhatsApp::first();
        // Ganti dengan device ID kamu (sesuai WHCenter)
        $this->deviceId =  $wa->token_whatsapp;
    }

    /**
     * Kirim pesan teks ke nomor WhatsApp.
     *
     * @param string $phone Nomor tujuan (contoh: 6285640206067)
     * @param string $message Pesan teks yang akan dikirim
     * @return array
     */
    public function sendMessage(string $phone, string $message): array
    {
        try {
            // Mengirim request sebagai form-data (sesuai dokumentasi WHCenter)
            $response = Http::asForm()->post($this->baseUrl, [
                'device_id' => $this->deviceId,
                'number' => $phone,
                'message' => $message,
            ]);

            if ($response->successful()) {
                return [
                    'status' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'status' => false,
                'error' => $response->json(),
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Kirim pesan dengan gambar (opsional).
     *
     * @param string $phone Nomor tujuan
     * @param string $caption Pesan atau caption
     * @param string $imageUrl URL gambar
     * @return array
     */
    public function sendImage(string $phone, string $caption, string $imageUrl): array
    {
        try {
            // Kirim form-data sesuai dokumentasi WHCenter
            $response = Http::asForm()->post($this->baseUrl, [
                'device_id' => $this->deviceId,
                'number' => $phone,
                'message' => $caption,
                'url' => $imageUrl,
            ]);

            if ($response->successful()) {
                return [
                    'status' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'status' => false,
                'error' => $response->json(),
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}

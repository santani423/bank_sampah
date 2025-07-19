<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
// Import the necessary Xendit API classes
use Xendit\Configuration;
// If you encounter "Class 'Xendit\Disbursement\DisbursementApi' not found" despite this 'use' statement,
// it's likely a Composer autoloader issue. Please run 'composer dump-autoload' and 'composer update'
// in your project's root directory to regenerate the autoloader files.
use Xendit\Disbursement\DisbursementApi;
use Xendit\Invoice\InvoiceApi;
use Xendit\ApiException;
use Illuminate\Support\Str;
use Exception;
// GuzzleHttp\Client is not strictly needed for basic setup if using Xendit's Configuration
// use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class TessController extends Controller
{
    protected InvoiceApi $invoiceApi;


    public function __construct()
    {
        $apiKey = config('xendit.api_key');

        if (empty($apiKey)) {
            Log::error('Xendit API Key is missing in configuration. Please ensure XENDIT_API_KEY is set in your .env file and configured in config/xendit.php.');
            abort(500, 'Xendit API Key tidak tersedia. Harap periksa konfigurasi aplikasi Anda.');
        }

        // Set the Xendit API key globally for the SDK
        Configuration::setXenditKey($apiKey);

        // Initialize the InvoiceApi (from previous context)
        $this->invoiceApi = new InvoiceApi();
    }

    /**
     * Handles the invoice creation process with Xendit.
     * (Existing method, included for context)
     *
     * @param Request $request The incoming HTTP request containing payment details.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function proses(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'jumlah' => 'required|numeric|min:1000',
        ]);

        $params = [
            'external_id' => 'invoice-' . time() . '-' . Str::random(6),
            'amount' => (int) $request->jumlah,
            'payer_email' => $request->email,
            'description' => 'Pembayaran oleh ' . $request->nama,
        ];

        try {
            $invoice = $this->invoiceApi->createInvoice(
                create_invoice_request: $params
            );

            if (isset($invoice['invoice_url'])) {
                return redirect()->away($invoice['invoice_url']);
            } else {
                Log::error('Invoice URL not found in Xendit response.', ['response' => $invoice]);
                return back()->withErrors(['xendit_error' => 'Gagal mendapatkan URL pembayaran dari Xendit. Respons tidak mengandung invoice_url.']);
            }
        } catch (Exception $e) {
            Log::error('General error during Xendit invoice creation: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withErrors(['error' => 'Terjadi kesalahan umum saat membuat invoice: ' . $e->getMessage()]);
        }
    }

    public function createDanaDisbursement(Request $request)
    {
        // Validasi input
        $request->validate([
            'amount' => 'required|numeric|min:10000',
            'phone_number' => 'required|string|regex:/^628[0-9]{7,11}$/',
            'account_holder_name' => 'required|string|max:255',
            'remark' => 'nullable|string|max:255'
        ]);

        // Generate external_id unik
        $externalId = 'disb-dana-' . time() . '-' . Str::random(5);

        // Buat payload sesuai format disbursement Xendit
        $payload = [
            'external_id' => $externalId,
            'amount' => (int) $request->amount,
            'bank_code' => 'DANA', // ✅ HARUS: gunakan DANA sebagai bank_code
            'account_holder_name' => $request->account_holder_name,
            'account_number' => $request->phone_number, // ✅ HARUS: ini nomor telepon penerima
            'description' => $request->remark ?? 'Disbursement ke DANA'
        ];

        try {
            $response = Http::withBasicAuth(config('xendit.api_key'), '')
                ->post('https://api.xendit.co/disbursements', $payload);

            if ($response->successful()) {
                Log::info('DANA Disbursement berhasil', [
                    'external_id' => $externalId,
                    'response' => $response->json()
                ]);

                return response()->json([
                    'message' => 'Disbursement DANA berhasil dikirim',
                    'data' => $response->json()
                ]);
            } else {
                Log::error('Gagal kirim DANA Disbursement', [
                    'payload' => $payload,
                    'response' => $response->json()
                ]);

                return response()->json([
                    'message' => 'Gagal kirim DANA Disbursement',
                    'error' => $response->json()
                ], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Exception saat kirim DANA Disbursement: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Gagal kirim DANA Disbursement',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

// {
//   "amount": 100000,
//   "phone_number": "6285778674418",
//   "account_holder_name": "Santani"
// }


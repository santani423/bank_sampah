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
        }  catch (Exception $e) {
            Log::error('General error during Xendit invoice creation: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withErrors(['error' => 'Terjadi kesalahan umum saat membuat invoice: ' . $e->getMessage()]);
        }
    }

    /**
     * Creates a DANA disbursement using Xendit.
     *
     * @param Request $request The incoming HTTP request with disbursement details.
     * @return \Illuminate\Http\JsonResponse
     */
    public function createDanaDisbursement(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000',
            'phone_number' => 'required|string|regex:/^628[0-9]{7,11}$/'
        ]);

        $referenceId = 'dana-ewallet-' . time() . '-' . Str::random(5);

        $response = Http::withBasicAuth(config('xendit.api_key'), '')
            ->post('https://api.xendit.co/ewallets/charges', [
                'reference_id' => $referenceId,
                'currency' => 'IDR',
                'amount' => (int) $request->amount,
                'checkout_method' => 'ONE_TIME_PAYMENT',
                'channel_code' => 'ID_DANA',
                'channel_properties' => [
                    'mobile_number' => $request->phone_number,
                    'success_redirect_url' => url('/dana/success')
                ]
            ]);

        if ($response->successful()) {
            return response()->json([
                'message' => 'Disbursement DANA berhasil dikirim',
                'data' => $response->json()
            ]);
        } else {
            return response()->json([
                'message' => 'Gagal kirim DANA',
                'error' => $response->json()
            ], 500);
        }
    }
}

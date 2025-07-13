<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Illuminate\Support\Str;
use Exception;

class TessController extends Controller
{
    protected InvoiceApi $invoiceApi;

    public function __construct()
    {
        $apiKey = config('xendit.api_key');

        if (empty($apiKey)) {
            Log::error('Xendit API Key not set in config.');
            abort(500, 'Xendit API Key tidak tersedia.');
        }

        Configuration::setXenditKey($apiKey);
        $this->invoiceApi = new InvoiceApi();
    }

    public function proses(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'jumlah' => 'required|numeric|min:1000',
        ]);

        $params = [
            'external_id' => 'invoice-' . time() . '-' . Str::random(6),
            'amount' => (int) $validated['jumlah'],
            'payer_email' => $validated['email'],
            'description' => 'Pembayaran oleh ' . $validated['nama'],
        ];

        try {
            $invoice = $this->invoiceApi->createInvoice(create_invoice_request: $params);
            return redirect()->away($invoice['invoice_url'] ?? '/');
        } catch (Exception $e) {
            Log::error('Xendit error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withErrors(['xendit_error' => 'Gagal membuat invoice: ' . $e->getMessage()]);
        }
    }
}

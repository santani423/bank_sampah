<?php

namespace App\Http\Controllers;

use App\Models\PengirimanLapak;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFController extends Controller
{

    public function invoiceKirimSampahLapak($kode)
    {
        $invoice = PengirimanLapak::with([
            'detailPengirimanLapaks.transaksiLapak.detailTransaksiLapak.sampah',
            'gudang',
            'petugas'
        ])
            ->where('kode_pengiriman', $kode)
            ->firstOrFail(); // jika tidak ada → 404

        $pdf = Pdf::loadView(
            'pages.petugas.invoic.pdf',
            compact('invoice')
        )->setPaper('A4', 'portrait');

        return $pdf->stream(
            'invoice-pengiriman-' . $kode . '.pdf'
        );
    }
}

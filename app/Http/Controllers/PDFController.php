<?php

namespace App\Http\Controllers;

use App\Models\PencairanLapak;
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

    public function invoicePenerimaanSampahLapak($kode)
    {
        $invoice = PengirimanLapak::with([
            'detailPengirimanLapaks.transaksiLapak.detailTransaksiLapak.sampah',
            'gudang',
            'petugas'
        ])
            ->where('kode_pengiriman', $kode)
            ->firstOrFail(); // jika tidak ada → 404

        $pdf = Pdf::loadView(
            'pages.petugas.invoic.pdf-penerimaan',
            compact('invoice')
        )->setPaper('A4', 'portrait');

        return $pdf->stream(
            'invoice-penerimaan-sampah-lapak-' . $kode . '.pdf'
        );
    }

    public function invoicePencairanLapak($kode)
    {
        $pencairan = PencairanLapak::with([
            'pengirimanLapak.detailPengirimanLapaks.transaksiLapak.detailTransaksiLapak',
            'pengirimanLapak.gudang.cabang',
            'pengirimanLapak.lapak',
            'pengirimanLapak.petugas'
        ])->where('kode_pencairan', $kode)->firstOrFail();

        // Akses langsung
        $pengiriman = $pencairan->pengirimanLapak;
        // dd($pencairan);

        // Ambil cabang dari relasi gudang
        $cabang = $pengiriman && $pengiriman->gudang ? $pengiriman->gudang->cabang : null;
        $pdf = Pdf::loadView(
            'pages.pdf.lapak-pencairan',
            compact('pengiriman', 'cabang', 'kode', 'pencairan')
        )->setPaper('A4', 'portrait');

        return $pdf->stream(
            'invoice-pencairan-lapak.pdf'
        );
    }
}

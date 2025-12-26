@extends('layouts.template')

@section('title', 'Invoice Kirim Sampah Lapak')


@section('main')
    <style>
        /* PRINT FORMAT */
        @media print {
            body * {
                visibility: hidden !important;
            }
            #invoice-area, #invoice-area * {
                visibility: visible !important;
            }
            #invoice-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100vw;
                min-height: 100vh;
                background: #fff !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .card, .invoice-card {
                box-shadow: none !important;
                border: 1px solid #bbb !important;
                margin-bottom: 24px !important;
            }
            .card-header, .card-body {
                background: #fff !important;
                color: #000 !important;
            }
            .invoice-title-white, .invoice-kode-white {
                color: #000 !important;
                text-shadow: none !important;
            }
            .btn, .d-flex.justify-content-center, .mb-5, .navbar, .sidebar, .preloader {
                display: none !important;
            }
            table {
                font-size: 13px !important;
            }
            th, td {
                padding: 6px 8px !important;
            }
        }
        .invoice-center {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .invoice-card {
            max-width: 700px;
            width: 100%;
        }
        .invoice-table th, .invoice-table td {
            vertical-align: middle !important;
        }
        .invoice-title-white, .invoice-kode-white {
            color: #fff !important;
            text-shadow: 0 1px 4px rgba(0,0,0,0.15);
        }
    </style>
    <div class="container mt-4 invoice-center" id="invoice-area">
        <div class="card shadow-lg border-0 mb-4 animate__animated animate__fadeIn invoice-card">
            <div class="card-header bg-gradient bg-primary d-flex align-items-center justify-content-between">
                <h3 class="mb-0 invoice-title-white"><i class="bi bi-receipt-cutoff me-2"></i>Invoice Pengiriman Sampah ke Lapak</h3>
                <span class="badge bg-light text-primary fs-6"><i class="bi bi-truck me-1"></i> Pengiriman</span>
            </div>
            <div class="card-body">
                <div class="row mb-3 justify-content-center">
                    <div class="col-md-6 mb-2">
                        <div class="p-3 rounded bg-light mb-2 h-100 d-flex flex-column justify-content-center">
                            <strong><i class="bi bi-upc-scan me-1"></i> Kode Pengiriman:</strong> <span>{{ $invoice->kode_pengiriman ?? '-' }}</span><br>
                            <strong><i class="bi bi-calendar-event me-1"></i> Tanggal:</strong> <span>{{ $invoice->tanggal_pengiriman ?? '-' }}</span><br>
                            <strong><i class="bi bi-person-badge me-1"></i> Petugas:</strong> <span>{{ $invoice->petugas->nama ?? '-' }}</span><br>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="p-3 rounded bg-light mb-2 h-100 d-flex flex-column justify-content-center">
                            <strong><i class="bi bi-shop-window me-1"></i> Lapak Tujuan:</strong> <span>{{ $invoice->gudang->nama_gudang ?? '-' }}</span><br>
                            <strong><i class="bi bi-geo-alt me-1"></i> Alamat Lapak:</strong> <span>{{ $invoice->gudang->alamat ?? '-' }}</span><br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @foreach ($invoice->detailPengirimanLapaks ?? [] as $i => $detail)
            <div class="card shadow-lg border-0 mb-4 animate__animated animate__fadeInUp invoice-card">
                <div class="card-header bg-gradient bg-success d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 invoice-kode-white"><i class="bi bi-box-seam me-2"></i>{{ $detail->transaksiLapak->kode_transaksi ?? '-' }}</h4>
                    <span class="badge bg-light text-success fs-6"><i class="bi bi-arrow-right-circle me-1"></i> Transaksi</span>
                </div>
                <div class="card-body">
                    <div class="row mb-3 justify-content-center">
                        <div class="col-md-8 mb-2">
                            <div class="p-2 rounded bg-light h-100 d-flex flex-column justify-content-center">
                                <strong><i class="bi bi-calendar-event me-1"></i> Tanggal:</strong>
                                <span>{{ $detail->transaksiLapak->tanggal_transaksi ?? '-' }}</span><br>
                                <strong><i class="bi bi-person-badge me-1"></i> Petugas:</strong> <span>{{ $invoice->petugas->nama ?? '-' }}</span><br>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0 invoice-table">
                            <thead class="table-success">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center"><i class="bi bi-recycle"></i> Jenis Sampah</th>
                                    <th class="text-center"><i class="bi bi-basket"></i> Berat (kg)</th>
                                    <th class="text-center"><i class="bi bi-cash-coin"></i> Harga Satuan</th>
                                    <th class="text-center"><i class="bi bi-currency-exchange"></i> Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($detail->transaksiLapak->detailTransaksiLapak ?? [] as $j => $transaksiDetail)
                                    <tr>
                                        <td class="text-center">{{ $j + 1 }}</td>
                                        <td class="text-center">{{ $transaksiDetail->sampah->nama_sampah ?? '-' }}</td>
                                        <td class="text-end">{{ $transaksiDetail->berat_kg ?? 0 }}</td>
                                        <td class="text-end">Rp {{ number_format($transaksiDetail->harga_per_kg ?? 0, 0, ',', '.') }}</td>
                                        <td class="text-end fw-bold">Rp {{ number_format($transaksiDetail->total_harga ?? 0, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="container mb-5 d-flex justify-content-center">
        <button class="btn btn-danger btn-lg shadow" onclick="printInvoice()">
            <i class="bi bi-printer me-2"></i> Print PDF
        </button>
    </div>

    <script>
        function printInvoice() {
            // Print hanya area invoice
            var printContents = document.getElementById('invoice-area').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }
    </script>
@endsection

@extends('layouts.template')

@section('title', 'Invoice Kirim Sampah Lapak')

@section('main')
    <div class="container mt-4">
        <div class="card shadow-lg border-0 mb-4 animate__animated animate__fadeIn">
            <div class="card-header bg-gradient bg-primary text-white d-flex align-items-center justify-content-between">
                <h3 class="mb-0"><i class="bi bi-receipt-cutoff me-2"></i>Invoice Pengiriman Sampah ke Lapak</h3>
                <span class="badge bg-light text-primary fs-6"><i class="bi bi-truck me-1"></i> Pengiriman</span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6 mb-2">
                        <div class="p-3 rounded bg-light mb-2">
                            <strong><i class="bi bi-upc-scan me-1"></i> Kode Pengiriman:</strong> <span>{{ $invoice->kode_pengiriman ?? '-' }}</span><br>
                            <strong><i class="bi bi-calendar-event me-1"></i> Tanggal:</strong> <span>{{ $invoice->tanggal_pengiriman ?? '-' }}</span><br>
                            <strong><i class="bi bi-person-badge me-1"></i> Petugas:</strong> <span>{{ $invoice->petugas->nama ?? '-' }}</span><br>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2 text-md-end">
                        <div class="p-3 rounded bg-light mb-2">
                            <strong><i class="bi bi-shop-window me-1"></i> Lapak Tujuan:</strong> <span>{{ $invoice->gudang->nama_gudang ?? '-' }}</span><br>
                            <strong><i class="bi bi-geo-alt me-1"></i> Alamat Lapak:</strong> <span>{{ $invoice->gudang->alamat ?? '-' }}</span><br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @foreach ($invoice->detailPengirimanLapaks ?? [] as $i => $detail)
            <div class="card shadow-lg border-0 mb-4 animate__animated animate__fadeInUp">
                <div class="card-header bg-gradient bg-success text-white d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-box-seam me-2"></i>{{ $detail->transaksiLapak->kode_transaksi ?? '-' }}</h4>
                    <span class="badge bg-light text-success fs-6"><i class="bi bi-arrow-right-circle me-1"></i> Transaksi</span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6 mb-2">
                            <div class="p-2 rounded bg-light">
                                <strong><i class="bi bi-calendar-event me-1"></i> Tanggal:</strong>
                                <span>{{ $detail->transaksiLapak->tanggal_transaksi ?? '-' }}</span><br>
                                <strong><i class="bi bi-person-badge me-1"></i> Petugas:</strong> <span>{{ $invoice->petugas->nama ?? '-' }}</span><br>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
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
                                        <td>{{ $transaksiDetail->sampah->nama_sampah ?? '-' }}</td>
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
@endsection

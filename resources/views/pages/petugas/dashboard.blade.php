@extends('layouts.template')

@section('title', 'Dashboard Petugas')

@push('style')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .card-stats {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }
        .card-stats:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }
        .icon-circle {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: white;
        }
        .bg-saldo { background: #0d6efd; }
        .bg-nasabah { background: #20c997; }
        .bg-transaksi { background: #ffc107; }
        .bg-sampah { background: #6f42c1; }
        .bg-tabungan { background: #198754; }
        .table thead {
            background-color: #0d6efd;
            color: #fff;
        }
    </style>
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-2">Dashboard Petugas</h3>
            <h6 class="text-muted mb-0">Ringkasan data dan aktivitas harian</h6>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-circle bg-saldo me-3">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div>
                        <p class="card-category mb-1 fw-semibold text-secondary">Saldo</p>
                        <h4 class="card-title mb-0">Rp {{ number_format($saldoPetugas->saldo ?? 0, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-3">
            <div class="card card-stats">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-circle bg-nasabah me-3">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <p class="card-category mb-1 fw-semibold text-secondary">Total Nasabah</p>
                        <h4 class="card-title mb-0">{{ $totalNasabah }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-3">
            <div class="card card-stats">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-circle bg-transaksi me-3">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div>
                        <p class="card-category mb-1 fw-semibold text-secondary">Setoran Hari Ini</p>
                        <h4 class="card-title mb-0">{{ $totalTransaksiHariIni }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-3">
            <div class="card card-stats">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-circle bg-sampah me-3">
                        <i class="bi bi-trash-fill"></i>
                    </div>
                    <div>
                        <p class="card-category mb-1 fw-semibold text-secondary">Jumlah Sampah Hari Ini</p>
                        <h4 class="card-title mb-0">{{ $totalSampahHariIni }} Kg</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-3">
            <div class="card card-stats">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-circle bg-tabungan me-3">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div>
                        <p class="card-category mb-1 fw-semibold text-secondary">Saldo Tabungan Nasabah</p>
                        <h4 class="card-title mb-0">Rp {{ number_format($totalOmzetHariIni, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Nasabah Terbaik --}}
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-star-fill me-2"></i>Top 10 Nasabah Terbaik Bulanan</h5>
                </div>
                <div class="card-body">
                    @if ($nasabahTerbaik->isEmpty())
                        <p class="text-center text-muted mb-0">Belum ada data nasabah terbaik bulan ini.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="text-center">
                                    <tr>
                                        <th>Nama Nasabah</th>
                                        <th>Total Sampah (Kg)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($nasabahTerbaik as $nasabah)
                                        @if ($nasabah->transaksi->sum('total_sampah') > 0)
                                            <tr>
                                                <td>{{ $nasabah->nama_lengkap }}</td>
                                                <td class="text-center fw-bold">{{ $nasabah->transaksi->sum('total_sampah') }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

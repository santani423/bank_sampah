@extends('layouts.template')

@section('title', 'Top Up Saldo Utama')

@push('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
    /* ==== Global Layout ==== */
    .container-fluid {
        max-width: 1200px;
    }

    /* ==== Saldo Card ==== */
    .saldo-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px;
        padding: 40px 45px;
        box-shadow: 0 12px 30px rgba(102, 126, 234, 0.35);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .saldo-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 45px rgba(102, 126, 234, 0.45);
    }
    .saldo-amount {
        font-size: 2.6rem;
        font-weight: 700;
        margin: 10px 0 5px;
    }

    /* ==== Status Badges ==== */
    .status-badge {
        padding: 7px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .status-pending { background-color: #ffc107; color: #000; }
    .status-success { background-color: #28a745; color: #fff; }
    .status-failed  { background-color: #dc3545; color: #fff; }
    .status-expired { background-color: #6c757d; color: #fff; }

    /* ==== Table ==== */
    .table thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #495057;
        border-bottom: 2px solid #dee2e6;
        padding: 14px;
        text-transform: uppercase;
        font-size: 0.83rem;
        letter-spacing: 0.4px;
        text-align: center;
    }
    .table td {
        padding: 14px;
        vertical-align: middle;
        text-align: center;
    }
    .table td.text-end {
        text-align: right !important;
    }
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* ==== Card ==== */
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }
    .card-header {
        background-color: #fff;
        border-bottom: 1px solid #e9ecef;
        border-radius: 15px 15px 0 0 !important;
        padding: 1rem 1.5rem;
    }
    .card-body {
        padding: 1.5rem;
    }

    /* ==== Buttons ==== */
    .btn {
        border-radius: 10px;
    }
    .btn-group .btn {
        padding: 6px 10px;
    }

    /* ==== Header Section ==== */
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
    }
    .page-header h3 {
        margin-bottom: 5px;
    }
</style>
@endpush

@section('main')
<div class="container-fluid">
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <div>
                    <h3 class="fw-bold mb-2">Top Up Saldo Utama</h3>
                    <p class="text-muted mb-0">Kelola saldo utama Bank Sampah</p>
                </div>
                <a href="{{ route('admin.topup.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="bi bi-plus-circle"></i> <span>Top Up Saldo</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Saldo Utama Card --}}
    <div class="row mb-5">
        <div class="col-lg-8 col-md-10 mx-auto">
            <div class="saldo-card">
                <div class="row align-items-center">
                    <div class="col-md-9">
                        <p class="mb-1 opacity-75">Saldo Utama Bank Sampah</p>
                        <div class="saldo-amount">
                            Rp {{ number_format($saldoUtama->saldo ?? 0, 0, ',', '.') }}
                        </div>
                        <p class="mb-0 mt-2 opacity-75">
                            <i class="bi bi-info-circle me-1"></i> 
                            {{ $saldoUtama->keterangan ?? 'Belum ada keterangan' }}
                        </p>
                    </div>
                    <div class="col-md-3 text-md-end text-center mt-3 mt-md-0">
                        <i class="bi bi-wallet2" style="font-size: 4.5rem; opacity: 0.25;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Riwayat Top Up --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0 fw-bold">Riwayat Top Up</h5>
                </div>
                <div class="card-body">

                    {{-- Alerts --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Table --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Admin</th>
                                    <th class="text-end">Jumlah</th>
                                    <th>Metode</th>
                                    <th>Status</th>
                                    <th>Tgl Bayar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topups as $topup)
                                    <tr>
                                        <td>{{ $topup->created_at->format('d M Y H:i') }}</td>
                                        <td class="text-start">
                                            <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                                                <i class="bi bi-person-circle me-2 fs-5"></i>
                                                <span>{{ $topup->user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <strong class="text-primary">Rp {{ number_format($topup->jumlah, 0, ',', '.') }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                <i class="bi bi-credit-card me-1"></i>{{ strtoupper($topup->metode_pembayaran) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($topup->status === 'success')
                                                <span class="status-badge status-success">
                                                    <i class="bi bi-check-circle me-1"></i>Berhasil
                                                </span>
                                            @elseif($topup->status === 'pending')
                                                <span class="status-badge status-pending">
                                                    <i class="bi bi-clock me-1"></i>Pending
                                                </span>
                                            @elseif($topup->status === 'failed')
                                                <span class="status-badge status-failed">
                                                    <i class="bi bi-x-circle me-1"></i>Gagal
                                                </span>
                                            @else
                                                <span class="status-badge status-expired">
                                                    <i class="bi bi-exclamation-circle me-1"></i>Kadaluarsa
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($topup->tanggal_bayar)
                                                {{ $topup->tanggal_bayar->format('d M Y H:i') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.topup.show', $topup->id) }}" 
                                                   class="btn btn-sm btn-info" title="Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @if($topup->status === 'pending' && $topup->xendit_invoice_url)
                                                    <a href="{{ $topup->xendit_invoice_url }}" 
                                                       target="_blank"
                                                       class="btn btn-sm btn-warning" title="Bayar">
                                                        <i class="bi bi-credit-card"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="bi bi-inbox" style="font-size: 4rem; color: #dee2e6;"></i>
                                                <p class="text-muted mt-3 mb-0">Belum ada riwayat top up</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($topups->hasPages())
                        <div class="mt-4 d-flex justify-content-center">
                            {{ $topups->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    setTimeout(() => $('.alert').fadeOut('slow'), 5000);
</script>
@endpush

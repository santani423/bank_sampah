@extends('layouts.template')

@section('title', 'Top Up Saldo Utama')

@push('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
    .saldo-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }
    
    .saldo-amount {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 10px 0;
    }
    
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .status-pending {
        background-color: #ffc107;
        color: #000;
    }
    
    .status-success {
        background-color: #28a745;
        color: #fff;
    }
    
    .status-failed {
        background-color: #dc3545;
        color: #fff;
    }
    
    .status-expired {
        background-color: #6c757d;
        color: #fff;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #495057;
    }
</style>
@endpush

@section('main')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h3 class="fw-bold mb-2">Top Up Saldo Utama</h3>
        <p class="text-muted mb-0">Kelola saldo utama Bank Sampah</p>
    </div>
    <a href="{{ route('admin.topup.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Top Up Saldo
    </a>
</div>

{{-- Saldo Utama Card --}}
<div class="row mb-4">
    <div class="col-md-6">
        <div class="saldo-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="mb-1 opacity-75">Saldo Utama Bank Sampah</p>
                    <div class="saldo-amount">
                        Rp {{ number_format($saldoUtama->saldo ?? 0, 0, ',', '.') }}
                    </div>
                    <p class="mb-0 opacity-75">
                        <i class="bi bi-info-circle"></i> 
                        {{ $saldoUtama->keterangan ?? 'Belum ada keterangan' }}
                    </p>
                </div>
                <div>
                    <i class="bi bi-wallet2" style="font-size: 4rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Riwayat Top Up --}}
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Riwayat Top Up</h5>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Admin</th>
                        <th>Jumlah</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Tanggal Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topups as $topup)
                        <tr>
                            <td>{{ $topup->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person-circle me-2"></i>
                                    {{ $topup->user->name }}
                                </div>
                            </td>
                            <td>
                                <strong>Rp {{ number_format($topup->jumlah, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    <i class="bi bi-credit-card"></i> {{ strtoupper($topup->metode_pembayaran) }}
                                </span>
                            </td>
                            <td>
                                @if($topup->status === 'success')
                                    <span class="status-badge status-success">
                                        <i class="bi bi-check-circle"></i> Berhasil
                                    </span>
                                @elseif($topup->status === 'pending')
                                    <span class="status-badge status-pending">
                                        <i class="bi bi-clock"></i> Pending
                                    </span>
                                @elseif($topup->status === 'failed')
                                    <span class="status-badge status-failed">
                                        <i class="bi bi-x-circle"></i> Gagal
                                    </span>
                                @else
                                    <span class="status-badge status-expired">
                                        <i class="bi bi-exclamation-circle"></i> Kadaluarsa
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
                                <a href="{{ route('admin.topup.show', $topup->id) }}" 
                                   class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                                @if($topup->status === 'pending' && $topup->xendit_invoice_url)
                                    <a href="{{ $topup->xendit_invoice_url }}" 
                                       target="_blank"
                                       class="btn btn-sm btn-warning">
                                        <i class="bi bi-credit-card"></i> Bayar
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-2">Belum ada riwayat top up</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $topups->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@endpush

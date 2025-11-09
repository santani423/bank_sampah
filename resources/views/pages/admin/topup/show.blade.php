@extends('layouts.template')

@section('title', 'Detail Top Up')

@push('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
    .detail-card {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }
    
    .detail-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
    }
    
    .info-row {
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .info-row:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 600;
        color: #666;
        margin-bottom: 5px;
    }
    
    .info-value {
        font-size: 1.1rem;
        color: #333;
    }
    
    .status-badge {
        padding: 8px 16px;
        border-radius: 25px;
        font-size: 1rem;
        font-weight: 600;
        display: inline-block;
    }
</style>
@endpush

@section('main')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.topup.index') }}" class="btn btn-outline-secondary me-3">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h3 class="fw-bold mb-1">Detail Top Up</h3>
        <p class="text-muted mb-0">Informasi lengkap transaksi top up</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="detail-card card">
            <div class="detail-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">
                            <i class="bi bi-receipt"></i> Top Up #{{ $topup->id }}
                        </h4>
                        <p class="mb-0 opacity-75">{{ $topup->created_at->format('d F Y, H:i') }} WIB</p>
                    </div>
                    <div>
                        @if($topup->status === 'success')
                            <span class="status-badge bg-success">
                                <i class="bi bi-check-circle"></i> Berhasil
                            </span>
                        @elseif($topup->status === 'pending')
                            <span class="status-badge bg-warning text-dark">
                                <i class="bi bi-clock"></i> Pending
                            </span>
                        @elseif($topup->status === 'failed')
                            <span class="status-badge bg-danger">
                                <i class="bi bi-x-circle"></i> Gagal
                            </span>
                        @else
                            <span class="status-badge bg-secondary">
                                <i class="bi bi-exclamation-circle"></i> Kadaluarsa
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="card-body p-4">
                {{-- Informasi Admin --}}
                <div class="info-row">
                    <div class="info-label">
                        <i class="bi bi-person"></i> Admin
                    </div>
                    <div class="info-value">{{ $topup->user->name }}</div>
                </div>
                
                {{-- Jumlah Top Up --}}
                <div class="info-row">
                    <div class="info-label">
                        <i class="bi bi-cash-stack"></i> Jumlah Top Up
                    </div>
                    <div class="info-value text-primary">
                        <strong>Rp {{ number_format($topup->jumlah, 0, ',', '.') }}</strong>
                    </div>
                </div>
                
                {{-- Metode Pembayaran --}}
                <div class="info-row">
                    <div class="info-label">
                        <i class="bi bi-credit-card"></i> Metode Pembayaran
                    </div>
                    <div class="info-value">
                        <span class="badge bg-info">{{ strtoupper($topup->metode_pembayaran) }}</span>
                    </div>
                </div>
                
                {{-- External ID --}}
                <div class="info-row">
                    <div class="info-label">
                        <i class="bi bi-hash"></i> ID Transaksi
                    </div>
                    <div class="info-value">
                        <code>{{ $topup->xendit_external_id }}</code>
                    </div>
                </div>
                
                {{-- Tanggal Bayar --}}
                @if($topup->tanggal_bayar)
                <div class="info-row">
                    <div class="info-label">
                        <i class="bi bi-calendar-check"></i> Tanggal Pembayaran
                    </div>
                    <div class="info-value">
                        {{ $topup->tanggal_bayar->format('d F Y, H:i') }} WIB
                    </div>
                </div>
                @endif
                
                {{-- Keterangan --}}
                @if($topup->keterangan)
                <div class="info-row">
                    <div class="info-label">
                        <i class="bi bi-sticky"></i> Keterangan
                    </div>
                    <div class="info-value">{{ $topup->keterangan }}</div>
                </div>
                @endif
                
                {{-- Payment URL --}}
                @if($topup->status === 'pending' && $topup->xendit_invoice_url)
                <div class="info-row">
                    <div class="info-label">
                        <i class="bi bi-link-45deg"></i> Link Pembayaran
                    </div>
                    <div class="info-value">
                        <a href="{{ $topup->xendit_invoice_url }}" 
                           target="_blank"
                           class="btn btn-warning btn-sm">
                            <i class="bi bi-box-arrow-up-right"></i> Buka Halaman Pembayaran
                        </a>
                    </div>
                </div>
                @endif
            </div>
            
            <div class="card-footer bg-light p-3">
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.topup.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    
                    @if($topup->status === 'pending' && $topup->xendit_invoice_url)
                    <a href="{{ $topup->xendit_invoice_url }}" 
                       target="_blank"
                       class="btn btn-warning">
                        <i class="bi bi-credit-card"></i> Lanjutkan Pembayaran
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

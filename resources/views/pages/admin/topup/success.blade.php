@extends('layouts.template')

@section('title', 'Pembayaran Berhasil')

@push('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
    .success-animation {
        text-align: center;
        padding: 50px 0;
    }
    
    .success-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 30px;
        background: linear-gradient(135deg, #4CAF50, #45a049);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: scaleIn 0.5s ease-out;
    }
    
    .success-icon i {
        font-size: 4rem;
        color: white;
    }
    
    @keyframes scaleIn {
        from {
            transform: scale(0);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }
    
    .success-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        padding: 40px;
        max-width: 600px;
        margin: 0 auto;
    }
</style>
@endpush

@section('main')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="success-card">
            <div class="success-animation">
                <div class="success-icon">
                    <i class="bi bi-check-lg"></i>
                </div>
                
                <h2 class="mb-3 fw-bold text-success">Pembayaran Sedang Diproses!</h2>
                <p class="text-muted mb-4">
                    Terima kasih telah melakukan top up. Pembayaran Anda sedang dalam proses verifikasi.
                </p>
                
                <div class="alert alert-info d-flex align-items-start" role="alert">
                    <i class="bi bi-info-circle me-2" style="font-size: 1.5rem;"></i>
                    <div class="text-start">
                        <strong>Informasi:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Silakan cek email Anda untuk detail pembayaran</li>
                            <li>Selesaikan pembayaran sesuai instruksi yang diberikan</li>
                            <li>Saldo akan otomatis bertambah setelah pembayaran dikonfirmasi</li>
                            <li>Proses verifikasi biasanya memakan waktu beberapa menit</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="d-flex gap-2 justify-content-center mt-4">
                <a href="{{ route('admin.topup.index') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-list-ul"></i> Lihat Riwayat Top Up
                </a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="bi bi-house"></i> Ke Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

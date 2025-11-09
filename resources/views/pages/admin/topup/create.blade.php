@extends('layouts.template')

@section('title', 'Form Top Up')

@push('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
    .topup-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .nominal-btn {
        border: 2px solid #667eea;
        color: #667eea;
        background: white;
        padding: 12px 20px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        margin: 5px;
    }
    
    .nominal-btn:hover,
    .nominal-btn.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }
    
    .info-box {
        background-color: #e7f3ff;
        border-left: 4px solid #2196F3;
        padding: 15px;
        border-radius: 5px;
    }
</style>
@endpush

@section('main')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.topup.index') }}" class="btn btn-outline-secondary me-3">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h3 class="fw-bold mb-1">Form Top Up Saldo</h3>
        <p class="text-muted mb-0">Isi saldo utama Bank Sampah</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        {{-- Info Box --}}
        <div class="info-box mb-4">
            <div class="d-flex align-items-start">
                <i class="bi bi-info-circle me-2" style="font-size: 1.5rem; color: #2196F3;"></i>
                <div>
                    <h6 class="mb-1">Informasi Top Up</h6>
                    <ul class="mb-0" style="padding-left: 20px;">
                        <li>Minimal top up Rp 10.000</li>
                        <li>Pembayaran menggunakan Xendit (Transfer Bank, E-Wallet, dll)</li>
                        <li>Invoice berlaku selama 24 jam</li>
                        <li>Saldo akan otomatis bertambah setelah pembayaran berhasil</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="card shadow">
            <div class="card-body p-4">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('admin.topup.store') }}" method="POST" id="topupForm">
                    @csrf
                    
                    {{-- Pilih Nominal --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-cash-stack"></i> Pilih Nominal Cepat
                        </label>
                        <div class="d-flex flex-wrap">
                            <button type="button" class="nominal-btn" data-nominal="10000">Rp 10.000</button>
                            <button type="button" class="nominal-btn" data-nominal="50000">Rp 50.000</button>
                            <button type="button" class="nominal-btn" data-nominal="100000">Rp 100.000</button>
                            <button type="button" class="nominal-btn" data-nominal="250000">Rp 250.000</button>
                            <button type="button" class="nominal-btn" data-nominal="500000">Rp 500.000</button>
                            <button type="button" class="nominal-btn" data-nominal="1000000">Rp 1.000.000</button>
                        </div>
                    </div>

                    {{-- Input Nominal --}}
                    <div class="mb-4">
                        <label for="jumlah" class="form-label fw-bold">
                            <i class="bi bi-wallet2"></i> Jumlah Top Up *
                        </label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text">Rp</span>
                            <input type="number" 
                                   class="form-control @error('jumlah') is-invalid @enderror" 
                                   id="jumlah"
                                   name="jumlah" 
                                   placeholder="Masukkan jumlah"
                                   value="{{ old('jumlah') }}"
                                   min="10000"
                                   required>
                        </div>
                        @error('jumlah')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Minimal Rp 10.000</small>
                    </div>

                    {{-- Keterangan --}}
                    <div class="mb-4">
                        <label for="keterangan" class="form-label fw-bold">
                            <i class="bi bi-pencil-square"></i> Keterangan (Opsional)
                        </label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                  id="keterangan"
                                  name="keterangan" 
                                  rows="3"
                                  placeholder="Contoh: Top up untuk operasional bulan Januari">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Info Payment --}}
                    <div class="topup-card">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-credit-card me-2" style="font-size: 1.5rem;"></i>
                            <h5 class="mb-0">Metode Pembayaran</h5>
                        </div>
                        <p class="mb-0 opacity-75">
                            Setelah submit, Anda akan diarahkan ke halaman pembayaran Xendit. 
                            Pilih metode pembayaran yang tersedia (Transfer Bank, E-Wallet, QRIS, dll).
                        </p>
                    </div>

                    {{-- Buttons --}}
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg flex-fill">
                            <i class="bi bi-check-circle"></i> Lanjutkan ke Pembayaran
                        </button>
                        <a href="{{ route('admin.topup.index') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Handle nominal button click
        $('.nominal-btn').click(function() {
            $('.nominal-btn').removeClass('active');
            $(this).addClass('active');
            
            const nominal = $(this).data('nominal');
            $('#jumlah').val(nominal);
        });
        
        // Format number input
        $('#jumlah').on('input', function() {
            // Remove active class from buttons when typing
            $('.nominal-btn').removeClass('active');
        });
        
        // Form validation
        $('#topupForm').on('submit', function(e) {
            const jumlah = parseInt($('#jumlah').val());
            
            if (!jumlah || jumlah < 10000) {
                e.preventDefault();
                alert('Jumlah top up minimal Rp 10.000');
                return false;
            }
            
            // Show loading
            $(this).find('button[type="submit"]').prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...'
            );
        });
    });
</script>
@endpush

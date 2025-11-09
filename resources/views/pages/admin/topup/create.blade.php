@extends('layouts.template')

@section('title', 'Form Top Up')

@push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        .topup-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 25px 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.25);
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .nominal-btn {
            border: 2px solid #667eea;
            color: #667eea;
            background: #fff;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin: 6px;
            min-width: 120px;
            text-align: center;
        }

        .nominal-btn:hover,
        .nominal-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(102, 126, 234, 0.35);
        }

        .info-box {
            background-color: #e9f3ff;
            border-left: 5px solid #2196F3;
            padding: 18px 20px;
            border-radius: 10px;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .btn-lg {
            padding: 12px 0;
            font-weight: 600;
        }

        .form-label i {
            color: #667eea;
        }

        @media (max-width: 576px) {
            .nominal-btn {
                flex: 1 1 45%;
                min-width: auto;
            }
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
            <p class="text-muted mb-0">Isi saldo utama Bank Sampah dengan mudah</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">

            {{-- Info Box --}}
            <div class="info-box mb-4">
                <div class="d-flex align-items-start">
                    <i class="bi bi-info-circle me-2" style="font-size: 1.4rem; color: #2196F3;"></i>
                    <div>
                        <h6 class="fw-bold mb-1">Informasi Top Up</h6>
                        <ul class="mb-0 ps-3 small text-muted">
                            <li>Minimal top up Rp 10.000</li>
                            <li>Pembayaran melalui Xendit (Transfer Bank, E-Wallet, QRIS, dll)</li>
                            <li>Invoice berlaku 24 jam</li>
                            <li>Saldo otomatis bertambah setelah pembayaran sukses</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <div class="card">
                <div class="card-body p-4">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.topup.store') }}" method="POST" id="topupForm">
                        @csrf

                        {{-- Pilihan Nominal --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-cash-stack me-1"></i> Pilih Nominal Cepat
                            </label>
                            <div class="d-flex flex-wrap justify-content-between">
                                <button type="button" class="nominal-btn" data-nominal="10000">Rp 10.000</button>
                                <button type="button" class="nominal-btn" data-nominal="50000">Rp 50.000</button>
                                <button type="button" class="nominal-btn" data-nominal="100000">Rp 100.000</button>
                                <button type="button" class="nominal-btn" data-nominal="250000">Rp 250.000</button>
                                <button type="button" class="nominal-btn" data-nominal="500000">Rp 500.000</button>
                                <button type="button" class="nominal-btn" data-nominal="1000000">Rp 1.000.000</button>
                            </div>
                        </div>

                        {{-- Input Jumlah --}}
                        <div class="mb-4">
                            <label for="jumlah" class="form-label fw-bold">
                                <i class="bi bi-wallet2 me-1"></i> Jumlah Top Up *
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">Rp</span>
                                <input type="number" id="jumlah" name="jumlah"
                                    class="form-control @error('jumlah') is-invalid @enderror"
                                    placeholder="Masukkan jumlah nominal" min="10000" value="{{ old('jumlah') }}"
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
                                <i class="bi bi-pencil-square me-1"></i> Keterangan (Opsional)
                            </label>
                            <textarea id="keterangan" name="keterangan" class="form-control" rows="3"
                                placeholder="Contoh: Top up untuk kegiatan operasional">{{ old('keterangan') }}</textarea>
                        </div>

                        {{-- Info Pembayaran --}}
                        <div class="topup-card mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-credit-card me-2" style="font-size: 1.6rem;"></i>
                                <h5 class="mb-0">Metode Pembayaran</h5>
                            </div>
                            <p class="mb-0 small opacity-75">
                                Setelah klik “Lanjutkan ke Pembayaran”, Anda akan diarahkan ke halaman Xendit
                                untuk memilih metode pembayaran seperti bank transfer, e-wallet, atau QRIS.
                            </p>
                        </div>

                        {{-- Tombol --}}
                        <div class="d-flex justify-content-center gap-3 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="bi bi-check-circle me-1"></i> Lanjutkan ke Pembayaran
                            </button>
                            <a href="{{ route('admin.topup.index') }}" class="btn btn-outline-secondary btn-lg px-5">
                                <i class="bi bi-x-circle me-1"></i> Batal
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
        $(function() {
            // Pilih nominal cepat
            $('.nominal-btn').on('click', function() {
                $('.nominal-btn').removeClass('active');
                $(this).addClass('active');
                $('#jumlah').val($(this).data('nominal'));
            });

            // Jika input manual, hapus active class
            $('#jumlah').on('input', function() {
                $('.nominal-btn').removeClass('active');
            });

            // Validasi & loading button
            $('#topupForm').on('submit', function(e) {
                const jumlah = parseInt($('#jumlah').val());
                if (!jumlah || jumlah < 10000) {
                    e.preventDefault();
                    alert('Jumlah top up minimal Rp 10.000');
                    return false;
                }
                $(this).find('button[type="submit"]').prop('disabled', true)
                    .html('<span class="spinner-border spinner-border-sm me-2"></span>Memproses...');
            });
        });
    </script>
@endpush

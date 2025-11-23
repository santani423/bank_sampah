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
        <a href="{{ route('admin.topup.index') }}" class="btn btn-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h3 class="fw-bold mb-1">Form Top Up Saldo</h3>
            <p class="text-muted mb-0">Isi saldo utama Bank Sampah dengan mudah</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">

            {{-- Alert jika ada pending topup --}}
            @if(isset($pendingTopup))
            <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                <div class="d-flex align-items-start">
                    <i class="bi bi-exclamation-triangle-fill me-2" style="font-size: 1.5rem;"></i>
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-2">Transaksi Top Up Pending</h6>
                        <p class="mb-2">Anda memiliki transaksi top up sebesar <strong>Rp {{ number_format($pendingTopup->jumlah, 0, ',', '.') }}</strong> yang belum diselesaikan (dibuat pada {{ $pendingTopup->created_at->format('d M Y H:i') }}).</p>
                        <div class="d-flex gap-2 mt-3">
                            <a href="{{ $pendingTopup->xendit_invoice_url }}" target="_blank" class="btn btn-sm btn-primary">
                                <i class="bi bi-credit-card me-1"></i> Lanjutkan Pembayaran
                            </a>
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="alert">
                                <i class="bi bi-x-circle me-1"></i> Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

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

                    <form id="topupForm">
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
                                <input type="text" id="jumlah" name="jumlah"
                                    class="form-control @error('jumlah') is-invalid @enderror"
                                    placeholder="Masukkan jumlah nominal" value="{{ old('jumlah') }}"
                                    required>
                                <input type="hidden" id="jumlah_raw" name="jumlah_raw">
                            </div>
                            <div class="invalid-feedback" id="jumlahError"></div>
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
                            <a href="{{ route('admin.topup.index') }}" class="btn btn-secondary btn-lg px-5">
                                <i class="bi bi-x-circle me-1"></i> Batal
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

    {{-- Modal Konfirmasi Pending Topup --}}
    <div class="modal fade" id="pendingTopupModal" tabindex="-1" aria-labelledby="pendingTopupModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="pendingTopupModalLabel">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Transaksi Pending Ditemukan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="bi bi-clock-history text-warning" style="font-size: 3rem;"></i>
                    </div>
                    <p class="text-center mb-3">Anda memiliki transaksi top up yang belum diselesaikan:</p>
                    <div class="alert alert-light border">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Jumlah:</span>
                            <strong class="text-primary" id="pendingAmount">-</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Dibuat:</span>
                            <span id="pendingDate">-</span>
                        </div>
                    </div>
                    <p class="text-center mb-0">Apa yang ingin Anda lakukan?</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-primary" id="btnContinuePending">
                        <i class="bi bi-arrow-right-circle me-1"></i> Lanjutkan Pembayaran
                    </button>
                    <button type="button" class="btn btn-danger" id="btnCreateNew">
                        <i class="bi bi-plus-circle me-1"></i> Buat Transaksi Baru
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            let pendingTopupData = null;
            let formDataToSubmit = null;

            // Function untuk format rupiah
            function formatRupiah(angka) {
                let number_string = angka.replace(/[^,\d]/g, '').toString();
                let split = number_string.split(',');
                let sisa = split[0].length % 3;
                let rupiah = split[0].substr(0, sisa);
                let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    let separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return rupiah;
            }

            // Function untuk convert format rupiah ke angka
            function unformatRupiah(rupiah) {
                return parseInt(rupiah.replace(/\./g, '')) || 0;
            }

            // Pilih nominal cepat
            $('.nominal-btn').on('click', function() {
                $('.nominal-btn').removeClass('active');
                $(this).addClass('active');
                let nominal = $(this).data('nominal');
                $('#jumlah').val(formatRupiah(nominal.toString()));
                $('#jumlah_raw').val(nominal);
            });

            // Format rupiah saat user mengetik
            $('#jumlah').on('keyup', function() {
                let nilai = $(this).val();
                $(this).val(formatRupiah(nilai));
                $('#jumlah_raw').val(unformatRupiah(nilai));
                $('.nominal-btn').removeClass('active');
            });

            // Function untuk submit form ke API
            function submitTopupForm(forceNew = false) {
                const submitBtn = $('#topupForm button[type="submit"]');
                
                // Disable button and show loading
                submitBtn.prop('disabled', true)
                    .html('<span class="spinner-border spinner-border-sm me-2"></span>Memproses...');

                // Get CSRF token
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                // Prepare data
                const requestData = {
                    jumlah: $('#jumlah_raw').val() || unformatRupiah($('#jumlah').val()),
                    keterangan: $('#keterangan').val()
                };

                // Jika force new, tambahkan parameter
                if (forceNew) {
                    requestData.force_new = true;
                }

                // Send AJAX request to API
                $.ajax({
                    url: '/api/admin/topup/store',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    data: JSON.stringify(requestData),
                    success: function(response) {
                        if (response.success && response.data.invoice_url) {
                            // Redirect to payment page
                            window.location.href = response.data.invoice_url;
                        } else {
                            // Show error
                            alert('Gagal membuat pembayaran');
                            submitBtn.prop('disabled', false)
                                .html('<i class="bi bi-check-circle me-1"></i> Lanjutkan ke Pembayaran');
                        }
                    },
                    error: function(xhr) {
                        // Handle error
                        let errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                        
                        // Jika status 409 (Conflict) berarti ada pending topup
                        if (xhr.status === 409 && xhr.responseJSON && xhr.responseJSON.has_pending) {
                            const pending = xhr.responseJSON.pending_topup;
                            pendingTopupData = pending;
                            
                            // Update modal content
                            $('#pendingAmount').text('Rp ' + parseInt(pending.jumlah).toLocaleString('id-ID'));
                            $('#pendingDate').text(pending.created_at);
                            
                            // Simpan data form untuk digunakan nanti jika user pilih buat baru
                            formDataToSubmit = {
                                jumlah: unformatRupiah($('#jumlah').val()),
                                keterangan: $('#keterangan').val()
                            };
                            
                            // Tampilkan modal
                            const modal = new bootstrap.Modal(document.getElementById('pendingTopupModal'));
                            modal.show();
                            
                            // Enable button
                            submitBtn.prop('disabled', false)
                                .html('<i class="bi bi-check-circle me-1"></i> Lanjutkan ke Pembayaran');
                            
                            return;
                        }
                        
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Validation errors
                            const errors = xhr.responseJSON.errors;
                            if (errors.jumlah) {
                                $('#jumlah').addClass('is-invalid');
                                $('#jumlahError').text(errors.jumlah[0]);
                            }
                            errorMessage = 'Periksa kembali data yang Anda masukkan.';
                        }

                        alert(errorMessage);
                        
                        // Enable button again
                        submitBtn.prop('disabled', false)
                            .html('<i class="bi bi-check-circle me-1"></i> Lanjutkan ke Pembayaran');
                    }
                });
            }

            // AJAX submit form
            $('#topupForm').on('submit', function(e) {
                e.preventDefault();

                const jumlah = unformatRupiah($('#jumlah').val());
                if (!jumlah || jumlah < 10000) {
                    $('#jumlah').addClass('is-invalid');
                    $('#jumlahError').text('Jumlah top up minimal Rp 10.000');
                    return false;
                }

                // Remove previous errors
                $('#jumlah').removeClass('is-invalid');
                $('#jumlahError').text('');

                // Submit form
                submitTopupForm(false);
            });

            // Handle button "Lanjutkan Pembayaran" di modal
            $('#btnContinuePending').on('click', function() {
                if (pendingTopupData && pendingTopupData.invoice_url) {
                    window.location.href = pendingTopupData.invoice_url;
                }
            });

            // Handle button "Buat Transaksi Baru" di modal
            $('#btnCreateNew').on('click', function() {
                // Tutup modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('pendingTopupModal'));
                modal.hide();
                
                // Submit dengan force_new = true
                submitTopupForm(true);
            });
        });
    </script>
@endpush

@extends('layouts.login')

@section('title', 'Lupa Password')
@section('favicon', asset($setting->logo))

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('asset/library/bootstrap-social/bootstrap-social.css') }}">
    <style>
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .loading-overlay.show {
            display: flex;
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
        }
    </style>
@endpush

@section('main')
    <section class="auth d-flex">
        <div class="auth-left bg-main-50 flex-center p-24">
            <img src="{{ asset('edmate/assets/images/thumbs/login.png') }}" alt="">
        </div>
        <div class="auth-right py-40 px-24 flex-center flex-column">
            <div class="auth-right__inner mx-auto w-100">
                <div style="display: flex; justify-content: center;">
                    <a href="/" class="auth-right__logo" style="width: 50%;">
                        <img src="{{ asset($setting->logo) }}" alt="" style="width: 100%; height: auto;" />
                    </a>
                </div>

                <h2 class="mb-8">Lupa Password</h2>
                <p class="mb-24 text-gray-600">Masukkan nomor WhatsApp Anda untuk menerima kode OTP</p>

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form id="forgotPasswordForm">
                    @csrf
                    <div class="mb-24">
                        <label for="no_hp" class="form-label mb-8 h6">Nomor WhatsApp</label>
                        <div class="position-relative">
                            <input type="number" class="form-control py-11 ps-40" id="no_hp" name="no_hp"
                                placeholder="Contoh: 628123456789" required>
                            <span class="position-absolute top-50 translate-middle-y ms-16 text-gray-600 d-flex">
                                <i class="ph ph-phone"></i>
                            </span>
                        </div>
                        <small class="text-muted">Format: 628xxxxxxxxxx (gunakan kode negara 62)</small>
                    </div>

                    <button type="submit" class="btn btn-main rounded-pill w-100" id="sendOtpBtn">
                        Kirim Kode OTP
                    </button>

                    <p class="mt-32 text-gray-600 text-center">Sudah ingat password?
                        <a href="{{ route('login') }}" class="text-main-600 hover-text-decoration-underline">Login</a>
                    </p>
                </form>
            </div>
        </div>
    </section>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#forgotPasswordForm').on('submit', function(e) {
                e.preventDefault();

                const phoneNumber = $('#no_hp').val().trim();

                // Validasi format nomor HP
                if (!phoneNumber.startsWith('62')) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Format Nomor Salah',
                        text: 'Nomor WhatsApp harus dimulai dengan kode negara 62',
                        confirmButtonColor: '#3085d6',
                    });
                    return;
                }

                // Show loading
                $('#loadingOverlay').addClass('show');
                $('#sendOtpBtn').prop('disabled', true);

                $.ajax({
                    url: "{{ route('forgot.password.send.otp') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        no_hp: phoneNumber
                    },
                    success: function(response) {
                        $('#loadingOverlay').removeClass('show');

                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'OTP Terkirim!',
                                text: response.message,
                                confirmButtonColor: '#3085d6',
                            }).then(() => {
                                window.location.href =
                                    "{{ route('forgot.password.reset.form') }}";
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message || 'Terjadi kesalahan',
                                confirmButtonColor: '#d33',
                            });
                            $('#sendOtpBtn').prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        $('#loadingOverlay').removeClass('show');
                        $('#sendOtpBtn').prop('disabled', false);

                        let errorMessage = 'Terjadi kesalahan';

                        if (xhr.status === 404) {
                            errorMessage = 'Nomor WhatsApp tidak terdaftar';
                        } else if (xhr.status === 422) {
                            errorMessage = 'Nomor WhatsApp wajib diisi';
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: errorMessage,
                            confirmButtonColor: '#d33',
                        });
                    }
                });
            });
        });
    </script>
@endpush

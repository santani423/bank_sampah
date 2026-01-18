@extends('layouts.login')

@section('title', 'Reset Password')
@section('favicon', asset($setting->logo))

@section('style')
    <style>
        .otp-input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin: 0 5px;
            border: 2px solid #ddd;
            border-radius: 8px;
        }
        .otp-input:focus {
            border-color: #007bff;
            outline: none;
        }
        .otp-container {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }
    </style>
@endsection

@section('main')
<section class="auth d-flex">
    <div class="auth-left bg-main-50 flex-center p-24">
        <img src="{{ asset('edmate/assets/images/thumbs/login.png') }}" alt="">
    </div>

    <div class="auth-right py-40 px-24 flex-center flex-column">
        <div class="auth-right__inner mx-auto w-100">

            <div class="text-center mb-24">
                <a href="/" class="auth-right__logo d-inline-block" style="width:50%">
                    <img src="{{ asset($setting->logo) }}" alt="Logo Bank Sampah" style="width:100%">
                </a>
            </div>

            <h2 class="mb-8 text-center">Reset Password</h2>
            <p class="text-center text-muted mb-4">
                Masukkan kode OTP yang telah dikirim ke<br>
                <strong>{{ $phoneNumber }}</strong>
            </p>

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('forgot.password.reset') }}" id="resetPasswordForm">
                @csrf

                <!-- OTP Input -->
                <label for="otp1" class="form-label mb-8 h6">Kode OTP</label>
                <div class="otp-container">
                    <input type="text" class="otp-input" maxlength="1" id="otp1" autofocus>
                    <input type="text" class="otp-input" maxlength="1" id="otp2">
                    <input type="text" class="otp-input" maxlength="1" id="otp3">
                    <input type="text" class="otp-input" maxlength="1" id="otp4">
                    <input type="text" class="otp-input" maxlength="1" id="otp5">
                    <input type="text" class="otp-input" maxlength="1" id="otp6">
                </div>

                <input type="hidden" name="otp_code" id="otp_code">

                @error('otp_code')
                    <div class="text-danger text-center mb-3">{{ $message }}</div>
                @enderror

                <div class="text-center mb-3">
                    <p class="mb-2">Tidak menerima kode?</p>
                    <button type="button" id="resendBtn" class="btn btn-link">
                        Kirim Ulang OTP
                    </button>
                    <p class="text-muted small" id="timerText"></p>
                </div>

                <!-- Password Input -->
                <div class="mb-24">
                    <label for="password" class="form-label mb-8 h6">Password Baru</label>
                    <div class="position-relative">
                        <input type="password" class="form-control py-11 ps-40 @error('password') is-invalid @enderror"
                            id="password" name="password" placeholder="Masukkan password baru" required>
                        <span class="toggle-password position-absolute top-50 inset-inline-end-0 me-16 translate-middle-y ph ph-eye-slash"
                            data-target="password"></span>
                        <span class="position-absolute top-50 translate-middle-y ms-16 text-gray-600 d-flex">
                            <i class="ph ph-lock"></i>
                        </span>
                        @error('password')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Confirm Password Input -->
                <div class="mb-24">
                    <label for="password_confirmation" class="form-label mb-8 h6">Konfirmasi Password</label>
                    <div class="position-relative">
                        <input type="password" class="form-control py-11 ps-40 @error('password_confirmation') is-invalid @enderror"
                            id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi password baru" required>
                        <span class="toggle-password position-absolute top-50 inset-inline-end-0 me-16 translate-middle-y ph ph-eye-slash"
                            data-target="password_confirmation"></span>
                        <span class="position-absolute top-50 translate-middle-y ms-16 text-gray-600 d-flex">
                            <i class="ph ph-lock"></i>
                        </span>
                        @error('password_confirmation')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-main w-100 rounded-pill mb-3">
                    Reset Password
                </button>

                <p class="mt-32 text-center">
                    <a href="{{ route('login') }}">Kembali ke Login</a>
                </p>
            </form>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // OTP Input Handler
    const otpInputs = document.querySelectorAll('.otp-input');
    const otpCodeInput = document.getElementById('otp_code');
    const form = document.getElementById('resetPasswordForm');
    const resendBtn = document.getElementById('resendBtn');
    const timerText = document.getElementById('timerText');

    // Auto focus next input
    otpInputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            if (e.target.value.length === 1) {
                if (index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            }
            updateOTPCode();
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && e.target.value === '') {
                if (index > 0) {
                    otpInputs[index - 1].focus();
                }
            }
        });

        // Only allow numbers
        input.addEventListener('keypress', (e) => {
            if (!/[0-9]/.test(e.key)) {
                e.preventDefault();
            }
        });

        // Handle paste
        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const pastedData = e.clipboardData.getData('text').slice(0, 6);
            if (/^\d+$/.test(pastedData)) {
                pastedData.split('').forEach((char, i) => {
                    if (otpInputs[i]) {
                        otpInputs[i].value = char;
                    }
                });
                updateOTPCode();
                otpInputs[Math.min(pastedData.length, 5)].focus();
            }
        });
    });

    function updateOTPCode() {
        let code = '';
        otpInputs.forEach(input => {
            code += input.value;
        });
        otpCodeInput.value = code;
    }

    // Resend OTP Timer
    let countdown = 60;
    let timer;

    function startTimer() {
        resendBtn.disabled = true;
        timer = setInterval(() => {
            countdown--;
            timerText.textContent = `Kirim ulang dalam ${countdown} detik`;

            if (countdown <= 0) {
                clearInterval(timer);
                resendBtn.disabled = false;
                timerText.textContent = '';
                countdown = 60;
            }
        }, 1000);
    }

    // Start timer on page load
    startTimer();

    // Resend OTP
    resendBtn.addEventListener('click', function() {
        $.ajax({
            url: "{{ route('forgot.password.resend.otp') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    startTimer();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message,
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat mengirim ulang OTP',
                });
            }
        });
    });

    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(toggle => {
        toggle.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const passwordInput = document.getElementById(targetId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.classList.remove('ph-eye-slash');
                this.classList.add('ph-eye');
            } else {
                passwordInput.type = 'password';
                this.classList.remove('ph-eye');
                this.classList.add('ph-eye-slash');
            }
        });
    });

    // Form validation
    form.addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        const otpCode = otpCodeInput.value;

        if (otpCode.length !== 6) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Kode OTP harus 6 digit',
            });
            return;
        }

        if (password.length < 6) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Password minimal 6 karakter',
            });
            return;
        }

        if (password !== confirmPassword) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Konfirmasi password tidak cocok',
            });
            return;
        }
    });
</script>
@endpush

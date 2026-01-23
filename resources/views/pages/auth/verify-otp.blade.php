@extends('layouts.login')

@section('title', 'Verifikasi OTP')
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
        <img src="{{ asset('edmate/assets/images/thumbs/register.png') }}" alt="">
    </div>

    <div class="auth-right py-40 px-24 flex-center flex-column">
        <div class="auth-right__inner mx-auto w-100">

            <div class="text-center mb-24">
                <a href="/" class="auth-right__logo d-inline-block" style="width:50%">
                    <img src="{{ asset($setting->logo) }}" style="width:100%">
                </a>
            </div>

            <h2 class="mb-8 text-center">Verifikasi OTP</h2>
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

            <form method="POST" action="{{ route('verify.otp') }}" id="otpForm">
                @csrf

                <div class="otp-container">
                    <input type="number" class="otp-input" maxlength="1" id="otp1" autofocus>
                    <input type="number" class="otp-input" maxlength="1" id="otp2">
                    <input type="number" class="otp-input" maxlength="1" id="otp3">
                    <input type="number" class="otp-input" maxlength="1" id="otp4">
                    <input type="number" class="otp-input" maxlength="1" id="otp5">
                    <input type="number" class="otp-input" maxlength="1" id="otp6">
                </div>

                <input type="hidden" name="otp_code" id="otp_code">

                @error('otp_code')
                    <div class="text-danger text-center mb-3">{{ $message }}</div>
                @enderror

                <button type="submit" class="btn btn-main w-100 rounded-pill mb-3">
                    Verifikasi
                </button>

                <div class="text-center">
                    <p class="mb-2">Tidak menerima kode?</p>
                    <button type="button" id="resendBtn" class="btn btn-link">
                        Kirim Ulang OTP
                    </button>
                    <p class="text-muted small" id="timerText"></p>
                </div>

                <p class="mt-32 text-center">
                    <a href="{{ route('register.form') }}">Kembali ke Registrasi</a>
                </p>
            </form>
        </div>
    </div>
</section>

{{-- SCRIPT --}}
<script>
    // OTP Input Handler
    const otpInputs = document.querySelectorAll('.otp-input');
    const otpCodeInput = document.getElementById('otp_code');
    const form = document.getElementById('otpForm');
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
        let otp = '';
        otpInputs.forEach(input => {
            otp += input.value;
        });
        otpCodeInput.value = otp;
    }

    // Resend OTP
    let canResend = false;
    let countdown = 60;

    function startCountdown() {
        canResend = false;
        resendBtn.disabled = true;
        
        const interval = setInterval(() => {
            countdown--;
            timerText.textContent = `Kirim ulang tersedia dalam ${countdown} detik`;
            
            if (countdown <= 0) {
                clearInterval(interval);
                canResend = true;
                resendBtn.disabled = false;
                timerText.textContent = '';
                countdown = 60;
            }
        }, 1000);
    }

    resendBtn.addEventListener('click', async () => {
        if (!canResend) return;
        
        resendBtn.disabled = true;
        resendBtn.textContent = 'Mengirim...';
        
        try {
            const response = await fetch('{{ route("resend.otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                alert(data.message);
                startCountdown();
            } else {
                alert(data.message || 'Gagal mengirim ulang OTP');
                resendBtn.disabled = false;
            }
        } catch (error) {
            alert('Terjadi kesalahan. Silakan coba lagi.');
            resendBtn.disabled = false;
        }
        
        resendBtn.textContent = 'Kirim Ulang OTP';
    });

    // Start countdown on page load
    startCountdown();

    // Auto submit when all 6 digits are entered
    form.addEventListener('submit', (e) => {
        if (otpCodeInput.value.length !== 6) {
            e.preventDefault();
            alert('Mohon masukkan 6 digit kode OTP');
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection

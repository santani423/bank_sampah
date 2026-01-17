@extends('layouts.login')

@section('title', 'Register')
@section('favicon', asset($setting->logo))

@push('style')
    <link rel="stylesheet" href="{{ asset('asset/library/bootstrap-social/bootstrap-social.css') }}">
@endpush

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

            <h2 class="mb-8">Register</h2>

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" id="registerForm">
                @csrf

                {{-- Nama --}}
                <div class="mb-24">
                    <label class="form-label h6">Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap"
                        class="form-control @error('nama_lengkap') is-invalid @enderror"
                        value="{{ old('nama_lengkap') }}">
                    @error('nama_lengkap')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Jenis Kelamin --}}
                <div class="mb-24">
                    <label class="form-label h6">Jenis Kelamin</label>
                    <select id="jenis_kelamin" name="jenis_kelamin"
                        class="form-control @error('jenis_kelamin') is-invalid @enderror">
                        <option value="">Pilih</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- No HP --}}
                <div class="mb-24">
                    <label class="form-label h6">No HP</label>
                    <input type="text" id="no_hp" name="no_hp"
                        class="form-control @error('no_hp') is-invalid @enderror"
                        value="{{ old('no_hp') }}">
                    @error('no_hp')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-24">
                    <label class="form-label h6">Email</label>
                    <input type="email" id="email" name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}">
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Username --}}
                <div class="mb-24">
                    <label class="form-label h6">Username</label>
                    <input type="text" id="username" name="username"
                        class="form-control @error('username') is-invalid @enderror"
                        value="{{ old('username') }}">
                    @error('username')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-24">
                    <label class="form-label h6">Password</label>
                    <input type="password" id="password" name="password"
                        class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div class="mb-24">
                    <label class="form-label h6">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation"
                        name="password_confirmation" class="form-control">
                </div>

                {{-- Alamat --}}
                <div class="mb-24">
                    <label class="form-label h6">Alamat Lengkap</label>
                    <textarea id="alamat_lengkap" name="alamat_lengkap"
                        class="form-control">{{ old('alamat_lengkap') }}</textarea>
                </div>

                {{-- Cabang --}}
                <div class="mb-24">
                    <label class="form-label h6">Cabang</label>
                    <select id="cabang_id" name="cabang_id"
                        class="form-control @error('cabang_id') is-invalid @enderror">
                        <option value="">Pilih Cabang</option>
                        @foreach ($cabangs as $cabang)
                            <option value="{{ $cabang->id }}">{{ $cabang->nama_cabang }}</option>
                        @endforeach
                    </select>
                    @error('cabang_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Button --}}
                <button type="button" id="btnRegister"
                    class="btn btn-main w-100 rounded-pill">
                    Register
                </button>

                <p class="mt-32 text-center">
                    Sudah punya akun?
                    <a href="{{ route('login') }}">Sign In</a>
                </p>
            </form>
        </div>
    </div>
</section>

{{-- MODAL KONFIRMASI --}}
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Registrasi</h5>
                <button type="button" class="btn-close"
                    data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <ul class="list-group">
                    <li class="list-group-item"><b>Nama:</b> <span id="cNama">-</span></li>
                    <li class="list-group-item"><b>No HP:</b> <span id="cHp">-</span></li>
                    <li class="list-group-item"><b>Email:</b> <span id="cEmail">-</span></li>
                    <li class="list-group-item"><b>Username:</b> <span id="cUsername">-</span></li>
                </ul>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary"
                    data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-main"
                    id="btnSubmitFinal">Kirim OTP</button>
            </div>

        </div>
    </div>
</div>

{{-- SCRIPT --}}
<script>
    const btnRegister = document.getElementById('btnRegister');
    const form = document.getElementById('registerForm');
    const btnSubmitFinal = document.getElementById('btnSubmitFinal');

    btnRegister.addEventListener('click', () => {
        const nama = document.getElementById('nama_lengkap').value || '-';
        const hp = document.getElementById('no_hp').value || '-';
        const email = document.getElementById('email').value || '-';
        const username = document.getElementById('username').value || '-';

        document.getElementById('cNama').innerText = nama;
        document.getElementById('cHp').innerText = hp;
        document.getElementById('cEmail').innerText = email;
        document.getElementById('cUsername').innerText = username;

        new bootstrap.Modal(
            document.getElementById('confirmModal')
        ).show();
    });

    btnSubmitFinal.addEventListener('click', async () => {
        // Disable button to prevent double click
        btnSubmitFinal.disabled = true;
        btnSubmitFinal.textContent = 'Mengirim OTP...';

        try {
            // Get form data
            const formData = new FormData(form);
            
            // Send OTP request
            const response = await fetch('{{ route("send.otp") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const data = await response.json();

            if (data.success) {
                // Redirect to OTP verification page
                window.location.href = '{{ route("verify.otp.form") }}';
            } else {
                alert(data.message || 'Terjadi kesalahan. Silakan coba lagi.');
                btnSubmitFinal.disabled = false;
                btnSubmitFinal.textContent = 'Konfirmasi & Daftar';
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan. Silakan coba lagi.');
            btnSubmitFinal.disabled = false;
            btnSubmitFinal.textContent = 'Konfirmasi & Daftar';
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection

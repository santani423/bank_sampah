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

            <form id="registerForm">
                @csrf

                {{-- Nama --}}
                <div class="mb-24">
                    <label class="form-label h6">Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control">
                    <div class="alert alert-danger mt-2 d-none" id="error-nama_lengkap"></div>
                </div>

                {{-- Jenis Kelamin --}}
                <div class="mb-24">
                    <label class="form-label h6">Jenis Kelamin</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" class="form-control">
                        <option value="">Pilih</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                    <div class="alert alert-danger mt-2 d-none" id="error-jenis_kelamin"></div>
                </div>

                {{-- No HP --}}
                <div class="mb-24">
                    <label class="form-label h6">No WhatsApp</label>
                    <input type="text" id="no_hp" name="no_hp" class="form-control" placeholder="Contoh: 628123456789" pattern="62[0-9]*" title="No HP harus diawali dengan 62 dan hanya berisi angka">
                    <small class="text-muted">Nomor harus diawali dengan 62</small>
                    <div class="alert alert-danger mt-2 d-none" id="error-no_hp"></div>
                </div>

                {{-- Email --}}
                <div class="mb-24">
                    <label class="form-label h6">Email</label>
                    <input type="email" id="email" name="email" class="form-control">
                    <div class="alert alert-danger mt-2 d-none" id="error-email"></div>
                </div>

                {{-- Username --}}
                <div class="mb-24">
                    <label class="form-label h6">Username</label>
                    <input type="text" id="username" name="username" class="form-control">
                    <div class="alert alert-danger mt-2 d-none" id="error-username"></div>
                </div>

                {{-- Password --}}
                <div class="mb-24">
                    <label class="form-label h6">Password</label>
                    <input type="password" id="password" name="password" class="form-control">
                    <div class="alert alert-danger mt-2 d-none" id="error-password"></div>
                </div>

                {{-- Konfirmasi Password --}}
                <div class="mb-24">
                    <label class="form-label h6">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                </div>

                {{-- Alamat --}}
                <div class="mb-24">
                    <label class="form-label h6">Alamat Lengkap</label>
                    <textarea id="alamat_lengkap" name="alamat_lengkap" class="form-control"></textarea>
                    <div class="alert alert-danger mt-2 d-none" id="error-alamat_lengkap"></div>
                </div>

                {{-- Cabang --}}
                <div class="mb-24">
                    <label class="form-label h6">Cabang</label>
                    <select id="cabang_id" name="cabang_id" class="form-control">
                        <option value="">Pilih Cabang</option>
                        @foreach ($cabangs as $cabang)
                            <option value="{{ $cabang->id }}">{{ $cabang->nama_cabang }}</option>
                        @endforeach
                    </select>
                    <div class="alert alert-danger mt-2 d-none" id="error-cabang_id"></div>
                </div>

                <button type="button" id="btnRegister" class="btn btn-main w-100 rounded-pill">
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
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <ul class="list-group">
                    <li class="list-group-item"><b>Nama:</b> <span id="cNama"></span></li>
                    <li class="list-group-item"><b>No HP:</b> <span id="cHp"></span></li>
                    <li class="list-group-item"><b>Email:</b> <span id="cEmail"></span></li>
                    <li class="list-group-item"><b>Username:</b> <span id="cUsername"></span></li>
                </ul>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-main" id="btnSubmitFinal">Kirim OTP</button>
            </div>

        </div>
    </div>
</div>

{{-- SCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
const form = document.getElementById('registerForm');
const btnRegister = document.getElementById('btnRegister');
const btnSubmitFinal = document.getElementById('btnSubmitFinal');

function resetErrors() {
    document.querySelectorAll('.alert-danger').forEach(el => {
        el.classList.add('d-none');
        el.innerText = '';
    });
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
}

function showError(field, message) {
    const input = document.getElementById(field);
    const error = document.getElementById(`error-${field}`);
    if (input) input.classList.add('is-invalid');
    if (error) {
        error.innerText = message;
        error.classList.remove('d-none');
    }
}

// Validasi No HP - hanya angka
document.getElementById('no_hp').addEventListener('input', function(e) {
    // Hapus karakter non-angka
    this.value = this.value.replace(/[^0-9]/g, '');
    
    // Validasi harus diawali dengan 62
    if (this.value.length > 0 && !this.value.startsWith('62')) {
        showError('no_hp', 'Nomor HP harus diawali dengan 62');
    } else {
        document.getElementById('error-no_hp').classList.add('d-none');
        this.classList.remove('is-invalid');
    }
});

btnRegister.addEventListener('click', () => {
    resetErrors();
    
    // Validasi No HP sebelum menampilkan modal
    const noHp = document.getElementById('no_hp').value;
    if (noHp && !noHp.startsWith('62')) {
        showError('no_hp', 'Nomor HP harus diawali dengan 62');
        return;
    }
    
    if (noHp && noHp.length < 10) {
        showError('no_hp', 'Nomor HP minimal 10 digit');
        return;
    }
    
    document.getElementById('cNama').innerText = nama_lengkap.value || '-';
    document.getElementById('cHp').innerText = no_hp.value || '-';
    document.getElementById('cEmail').innerText = email.value || '-';
    document.getElementById('cUsername').innerText = username.value || '-';

    new bootstrap.Modal(confirmModal).show();
});

btnSubmitFinal.addEventListener('click', async () => {
    btnSubmitFinal.disabled = true;
    btnSubmitFinal.innerText = 'Mengirim OTP...';
    resetErrors();

    try {
        const response = await fetch('{{ route("send.otp") }}', {
            method: 'POST',
            body: new FormData(form),
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });

        const data = await response.json();

        if (!response.ok && data.type === 'validation_error') {
            Object.entries(data.errors).forEach(([field, msgs]) => {
                showError(field, msgs[0]);
            });
            btnSubmitFinal.disabled = false;
            btnSubmitFinal.innerText = 'Kirim OTP';
            return;
        }

        if (!data.success) {
            alert(data.message || 'Terjadi kesalahan.');
            btnSubmitFinal.disabled = false;
            btnSubmitFinal.innerText = 'Kirim OTP';
            return;
        }

        window.location.href = '{{ route("verify.otp.form") }}';

    } catch (err) {
        alert('Terjadi kesalahan jaringan.');
        btnSubmitFinal.disabled = false;
        btnSubmitFinal.innerText = 'Kirim OTP';
    }
});
</script>
@endsection

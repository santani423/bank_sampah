@extends('layouts.template')

@section('title', 'Profile User')

@push('style')
<style>
    .profile-card {
        border-radius: 15px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 25px;
        background: #fff;
    }
    .profile-header {
        text-align: center;
        margin-bottom: 20px;
    }
    .profile-header img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #f0f0f0;
    }
</style>
@endpush

@section('main')
<div class="container mt-4">

    {{-- Alert sukses --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Alert error --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        {{-- Profile Section --}}
        <div class="col-md-5">
            <div class="profile-card">
                <div class="profile-header">
                    <img id="fotoPreview" 
                         src="{{ $user->foto ? asset('storage/foto/' . $user->foto) : asset('images/default-user.png') }}" 
                         alt="Profile Photo">
                    <h4 class="mt-2">{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                </div>
                <div>
                    <p><b>Username:</b> {{ $user->username }}</p>
                    <p><b>Role:</b> {{ $user->role }}</p>
                    <p><b>Terdaftar:</b> {{ $user->created_at->format('d-m-Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Edit Profile & Nasabah Section --}}
        <div class="col-md-7">
            <div class="profile-card">
                <h5 class="mb-3">Edit Profile & Data Nasabah</h5>
                <form action="{{ route('nasabah.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Bagian User --}}
                    <h6 class="fw-bold">Akun User</h6>
                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" class="form-control" name="name"
                            value="{{ old('name', $user->name) }}">
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email"
                            value="{{ old('email', $user->email) }}">
                    </div>
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" class="form-control" name="username"
                            value="{{ old('username', $user->username) }}">
                    </div>
                    <div class="mb-3">
                        <label>Password <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                        <input type="password" class="form-control" name="password">
                    </div>
                    <div class="mb-3">
                        <label>Foto Profile</label>
                        <input type="file" class="form-control" name="foto" accept="image/*" id="fotoInput">
                        <div class="mt-2">
                            <img id="fotoPreview" 
                                 src="{{ $user->foto ? asset('storage/foto/' . $user->foto) : asset('images/default-user.png') }}" 
                                 alt="Preview Foto" 
                                 style="max-width: 150px; max-height: 150px; border-radius: 10px; display: block;">
                        </div>
                    </div>

                    <hr>

                    {{-- Bagian Nasabah --}}
                    <h6 class="fw-bold">Data Nasabah</h6>
                    <div class="mb-3">
                        <label>Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama_lengkap"
                            value="{{ old('nama_lengkap', $nasabah->nama_lengkap) }}">
                    </div>
                    <div class="mb-3">
                        <label>Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control">
                            <option value="Laki-laki" {{ old('jenis_kelamin', $nasabah->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $nasabah->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Tempat Lahir</label>
                            <input type="text" class="form-control" name="tempat_lahir"
                                value="{{ old('tempat_lahir', $nasabah->tempat_lahir) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Tanggal Lahir</label>
                            <input type="date" class="form-control" name="tanggal_lahir"
                                value="{{ old('tanggal_lahir', $nasabah->tanggal_lahir) }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>No HP</label>
                        <input type="text" class="form-control" name="no_hp"
                            value="{{ old('no_hp', $nasabah->no_hp) }}">
                    </div>
                    <div class="mb-3">
                        <label>Alamat Lengkap</label>
                        <textarea name="alamat_lengkap" class="form-control">{{ old('alamat_lengkap', $nasabah->alamat_lengkap) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const fotoInput = document.getElementById('fotoInput');
    const fotoPreview = document.getElementById('fotoPreview');

    fotoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            // Cek tipe file
            if(!file.type.startsWith('image/')) {
                alert('Hanya file gambar yang diperbolehkan!');
                this.value = ''; // Reset input
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                fotoPreview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush

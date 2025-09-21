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

        .profile-info {
            margin-top: 15px;
        }

        .profile-info .row {
            margin-bottom: 10px;
        }
    </style>
@endpush

@section('main')
    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row">
            {{-- Profile Section --}}
            <div class="col-md-5">
                <div class="profile-card">
                    <div class="profile-header">
                        <img src="{{ $user->foto ? asset('storage/foto/' . $user->foto) : asset('images/default-user.png') }}"
                            alt="Profile Photo">
                        <h4 class="mt-2">{{ $user->name }}</h4>
                        <p class="text-muted">{{ $user->email }}</p>
                    </div>
                    <div class="profile-info">
                        <div class="row">
                            <div class="col-4 fw-bold">Username</div>
                            <div class="col-8">{{ $user->username }}</div>
                        </div>
                        <div class="row">
                            <div class="col-4 fw-bold">Role</div>
                            <div class="col-8">{{ $user->role }}</div>
                        </div>
                        <div class="row">
                            <div class="col-4 fw-bold">Terdaftar</div>
                            <div class="col-8">{{ $user->created_at->format('d-m-Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Edit Profile Section --}}
            <div class="col-md-7">
                <div class="profile-card">
                    <h5 class="mb-3">Edit Profile</h5>
                    <form action="{{ route('nasabah.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="name"
                                value="{{ old('name', $user->name) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email"
                                value="{{ old('email', $user->email) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username"
                                value="{{ old('username', $user->username) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password <small class="text-muted">(kosongkan jika tidak ingin
                                    diubah)</small></label>
                            <input type="password" class="form-control" name="password">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto Profile</label>
                            <input type="file" class="form-control" name="foto">
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
        // Tambahkan JS tambahan kalau diperlukan
    </script>
@endpush

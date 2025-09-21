@extends('layouts.template')

@section('title', 'Profile Nasabah')

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
        <div class="row">
            {{-- Profile Section --}}
            <div class="col-md-5">
                <div class="profile-card">
                    <div class="profile-header">
                        <img src="{{ $nasabah->photo ?? asset('images/default-user.png') }}" alt="Profile Photo">
                        <h4 class="mt-2">{{ $nasabah->nama_lengkap }}</h4>
                        <p class="text-muted">{{ $nasabah->email }}</p>
                    </div>
                    <div class="profile-info">
                        <div class="row">
                            <div class="col-4 fw-bold">No. Tlp</div>
                            <div class="col-8">{{ $nasabah->no_tlp }}</div>
                        </div>
                        <div class="row">
                            <div class="col-4 fw-bold">Alamat</div>
                            <div class="col-8">{{ $nasabah->alamat }}</div>
                        </div>
                        <div class="row">
                            <div class="col-4 fw-bold">Status</div>
                            <div class="col-8">{{ $nasabah->status_pernikahan }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Edit Profile Section --}}
            <div class="col-md-7">
                <div class="profile-card">
                    <h5 class="mb-3">Edit Profile</h5>
                    <form action="{{ route('nasabah.update', $nasabah->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama_lengkap"
                                value="{{ old('nama_lengkap', $nasabah->nama_lengkap) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email"
                                value="{{ old('email', $nasabah->email) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" class="form-control" name="no_tlp"
                                value="{{ old('no_tlp', $nasabah->no_tlp) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" rows="3">{{ old('alamat', $nasabah->alamat) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status Pernikahan</label>
                            <select name="status_pernikahan" class="form-select">
                                <option value="Lajang" {{ $nasabah->status_pernikahan == 'Lajang' ? 'selected' : '' }}>
                                    Lajang</option>
                                <option value="Menikah" {{ $nasabah->status_pernikahan == 'Menikah' ? 'selected' : '' }}>
                                    Menikah</option>
                                <option value="Cerai" {{ $nasabah->status_pernikahan == 'Cerai' ? 'selected' : '' }}>Cerai
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto Profile</label>
                            <input type="file" class="form-control" name="photo">
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
        // Bisa tambahkan JS tambahan jika perlu
    </script>
@endpush

@extends('layouts.template')

@section('title', 'Tambah Anggota Tim')

@push('style')
    <!-- CSS Libraries -->
    <style>
        #avatar-preview {
            display: none;
            margin-top: 10px;
            border-radius: 10px;
            max-width: 200px;
            height: auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Tambah Anggota Tim</h3>
            <h6 class="op-7 mb-2">Gunakan form di bawah untuk menambahkan data anggota tim baru.</h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <div class="section-header-button">
                <a href="{{ route('admin.time.index') }}" class="btn btn-secondary btn-round">Kembali</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('admin.time.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- NAMA --}}
                        <div class="form-group mb-3">
                            <label for="name">Nama</label>
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Masukkan nama anggota tim"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- AVATAR --}}
                        <div class="form-group mb-3">
                            <label for="avatar">Avatar (Foto Profil)</label>
                            <input type="file" name="avatar" id="avatar"
                                class="form-control @error('avatar') is-invalid @enderror"
                                accept="image/*">
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            {{-- PREVIEW GAMBAR --}}
                            <img id="avatar-preview" src="#" alt="Preview Avatar">
                        </div>

                        {{-- JABATAN --}}
                        <div class="form-group mb-3">
                            <label for="jabatan">Jabatan</label>
                            <input type="text" name="jabatan" id="jabatan"
                                class="form-control @error('jabatan') is-invalid @enderror"
                                placeholder="Masukkan jabatan (contoh: Manajer, Staf, dll)"
                                value="{{ old('jabatan') }}">
                            @error('jabatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- KETERANGAN --}}
                        <div class="form-group mb-3">
                            <label for="keterangan">Keterangan</label>
                            <textarea name="keterangan" id="keterangan"
                                class="form-control @error('keterangan') is-invalid @enderror"
                                placeholder="Tuliskan deskripsi singkat atau tugas anggota tim">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- TOMBOL --}}
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary btn-round">Simpan</button>
                            <a href="{{ route('admin.time.index') }}" class="btn btn-light btn-round">Batal</a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script>
        document.getElementById('avatar').addEventListener('change', function (event) {
            const file = event.target.files[0];
            const preview = document.getElementById('avatar-preview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                preview.src = '#';
                preview.style.display = 'none';
            }
        });
    </script>
@endpush

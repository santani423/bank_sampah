@extends('layouts.template')

@section('title', 'Tambah Lapak')

@push('style')
    <style>
        .preview-img {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            display: none;
        }
    </style>
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Form Tambah Lapak</h3>
            <h6 class="op-7 mb-2">Halaman untuk menambah data lapak baru</h6>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Informasi Lapak</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('petugas.lapak.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kode_lapak">Kode Lapak <span class="text-danger">*</span></label>
                                    <input type="text" id="kode_lapak"
                                        class="form-control @error('kode_lapak') is-invalid @enderror" name="kode_lapak"
                                        value="{{ old('kode_lapak', $kodeLapak) }}" required readonly>
                                    @error('kode_lapak')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-select.select-cabang name="cabang_id" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="alamat">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea id="alamat" class="form-control @error('alamat') is-invalid @enderror" name="alamat" rows="3"
                                required>{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nama_lapak">Nama Lapak <span class="text-danger">*</span></label>
                            <input type="text" id="nama_lapak"
                                class="form-control @error('nama_lapak') is-invalid @enderror" name="nama_lapak"
                                value="{{ old('nama_lapak') }}" required>
                            @error('nama_lapak')
                                <div class="form-group">
                                    <label for="nomor_rekening" class="form-label">Nomor Rekening <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="nomor_rekening" id="nomor_rekening"
                                        class="form-control @error('nomor_rekening') is-invalid @enderror" required>
                                    @error('nomor_rekening')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="kota">Kota</label>
                                    <input type="text" id="kota"
                                        class="form-control @error('kota') is-invalid @enderror" name="kota"
                                        value="{{ old('kota') }}">
                                    @error('kota')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="provinsi">Provinsi</label>
                                    <input type="text" id="provinsi"
                                        class="form-control @error('provinsi') is-invalid @enderror" name="provinsi"
                                        value="{{ old('provinsi') }}">
                                    @error('provinsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="kode_pos">Kode Pos</label>
                                    <input type="text" id="kode_pos"
                                        class="form-control @error('kode_pos') is-invalid @enderror" name="kode_pos"
                                        value="{{ old('kode_pos') }}" maxlength="10">
                                    @error('kode_pos')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="no_telepon">No. Telepon</label>
                                    <input type="text" id="no_telepon"
                                        class="form-control @error('no_telepon') is-invalid @enderror" name="no_telepon"
                                        value="{{ old('no_telepon') }}" placeholder="contoh: 081234567890">
                                    @error('no_telepon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select id="status" class="form-control @error('status') is-invalid @enderror"
                                        name="status" required>
                                        <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>
                                            Aktif</option>
                                        <option value="tidak_aktif"
                                            {{ old('status') == 'tidak_aktif' ? 'selected' : '' }}>
                                            Tidak Aktif</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea id="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi"
                                rows="4">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Opsional: Deskripsi singkat tentang lapak</small>
                        </div>

                        <div class="form-group">
                            <label for="foto">Foto Lapak</label>
                            <input type="file" id="foto"
                                class="form-control @error('foto') is-invalid @enderror" name="foto"
                                accept="image/jpeg,image/png,image/jpg" onchange="previewImage(event)">
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Format: JPG, JPEG, PNG. Max: 2MB</small>
                            <img id="preview" class="preview-img img-thumbnail" alt="Preview">
                        </div>

                        <div class="form-group">
                            {{-- <x-select.select-metode-bayar name="jenis_metode_penarikan_id" /> --}}
                            <label for="jenis_metode_penarikan_id">
                                Collation Center
                                <span class="text-danger">*</span>

                            </label>
                            <select id="jenis_metode_penarikan_id"
                                class="form-control @error($name) is-invalid @enderror" name="jenis_metode_penarikan_id"
                                required>
                                <option value="" disabled selected>Pilih Collation Center</option>
                                @php
                                    $selected = old($name, $value ?? '');
                                @endphp
                                @foreach ($cabang as $cabang)
                                    <option value="{{ $cabang->id }}" {{ $selected == $cabang->id ? 'selected' : '' }}>
                                        {{ $cabang->nama_cabang }} - {{ $cabang->kode_cabang }}
                                    </option>
                                @endforeach
                            </select>
                            @error($name)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>
                        <div class="form-group">
                            <label for="nama_rekening" class="form-label">Atas Nama <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="nama_rekening" id="nama_rekening"
                                class="form-control @error('nama_rekening') is-invalid @enderror" required>
                            @error('nama_rekening')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nomor_rekening" class="form-label">Nomor Rekening <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="nomor_rekening" id="nomor_rekening"
                                class="form-control @error('nomor_rekening') is-invalid @enderror" required>
                            @error('nomor_rekening')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                            <a href="{{ route('petugas.lapak.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function previewImage(event) {
            const preview = document.getElementById('preview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        }
    </script>
    @include('sweetalert::alert')
@endpush

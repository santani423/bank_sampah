@extends('layouts.template')

@section('title', 'Edit Lapak')

@push('style')
    <style>
        .preview-img {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
        }
    </style>
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Form Edit Lapak</h3>
            <h6 class="op-7 mb-2">Halaman untuk mengedit data lapak</h6>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Informasi Lapak</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('petugas.lapak.update', $lapak->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kode_lapak">Kode Lapak <span class="text-danger">*</span></label>
                                    <input type="text" id="kode_lapak"
                                        class="form-control @error('kode_lapak') is-invalid @enderror" name="kode_lapak"
                                        value="{{ old('kode_lapak', $lapak->kode_lapak) }}" required>
                                    @error('kode_lapak')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cabang_id">Cabang <span class="text-danger">*</span></label>
                                    <select id="cabang_id" class="form-control @error('cabang_id') is-invalid @enderror"
                                        name="cabang_id" required>
                                        <option value="" disabled>Pilih Cabang</option>
                                        @foreach ($cabangs as $cabang)
                                            <option value="{{ $cabang->id }}"
                                                {{ old('cabang_id', $lapak->cabang_id) == $cabang->id ? 'selected' : '' }}>
                                                {{ $cabang->nama_cabang }} - {{ $cabang->kode_cabang }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('cabang_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="nama_lapak">Nama Lapak <span class="text-danger">*</span></label>
                            <input type="text" id="nama_lapak"
                                class="form-control @error('nama_lapak') is-invalid @enderror" name="nama_lapak"
                                value="{{ old('nama_lapak', $lapak->nama_lapak) }}" required>
                            @error('nama_lapak')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="alamat">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea id="alamat" class="form-control @error('alamat') is-invalid @enderror" name="alamat" rows="3"
                                required>{{ old('alamat', $lapak->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="kota">Kota</label>
                                    <input type="text" id="kota"
                                        class="form-control @error('kota') is-invalid @enderror" name="kota"
                                        value="{{ old('kota', $lapak->kota) }}">
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
                                        value="{{ old('provinsi', $lapak->provinsi) }}">
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
                                        value="{{ old('kode_pos', $lapak->kode_pos) }}" maxlength="10">
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
                                        value="{{ old('no_telepon', $lapak->no_telepon) }}"
                                        placeholder="contoh: 081234567890">
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
                                        <option value="aktif"
                                            {{ old('status', $lapak->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="tidak_aktif"
                                            {{ old('status', $lapak->status) == 'tidak_aktif' ? 'selected' : '' }}>Tidak
                                            Aktif</option>
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
                                rows="4">{{ old('deskripsi', $lapak->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Opsional: Deskripsi singkat tentang lapak</small>
                        </div>

                        <div class="form-group">
                            <label for="foto">Foto Lapak</label>
                            @if ($lapak->foto)
                                <div class="mb-2">
                                    <img src="{{ asset('uploads/lapak/' . $lapak->foto) }}" class="img-thumbnail"
                                        style="max-width: 200px; max-height: 200px;" alt="Foto Lapak">
                                    <p class="text-muted small mt-1">Foto saat ini</p>
                                </div>
                            @endif
                            <input type="file" id="foto"
                                class="form-control @error('foto') is-invalid @enderror" name="foto"
                                accept="image/jpeg,image/png,image/jpg" onchange="previewImage(event)">
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Format: JPG, JPEG, PNG. Max: 2MB. Kosongkan jika tidak
                                ingin mengubah foto.</small>
                            <img id="preview" class="preview-img img-thumbnail" style="display: none;" alt="Preview">
                        </div>

                        <div class="form-group">
                            <label for="jenis_metode_penarikan_id" class="form-label">Jenis Metode <span class="text-danger">*</span></label>
                            <select name="jenis_metode_penarikan_id" id="jenis_metode_penarikan_id" class="form-select @error('jenis_metode_penarikan_id') is-invalid @enderror" required>
                                <option value="">Pilih Jenis Metode</option>
                                @foreach ($jenisMetodePenarikan as $jenis)
                                    <option value="{{ $jenis->id }}" {{ old('jenis_metode_penarikan_id', $lapak->jenis_metode_penarikan_id) == $jenis->id ? 'selected' : '' }}>{{ $jenis->nama }}</option>
                                @endforeach
                            </select>
                            @error('jenis_metode_penarikan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nama_rekening" class="form-label">Atas Nama <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="nama_rekening" id="nama_rekening"
                                class="form-control @error('nama_rekening') is-invalid @enderror"
                                value="{{ old('nama_rekening', $lapak->nama_rekening) }}" required>
                            @error('nama_rekening')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nomor_rekening" class="form-label">Nomor Rekening <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="nomor_rekening" id="nomor_rekening"
                                class="form-control @error('nomor_rekening') is-invalid @enderror "
                                value="{{ old('nomor_rekening', $lapak->nomor_rekening) }}" required>
                            @error('nomor_rekening')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update
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

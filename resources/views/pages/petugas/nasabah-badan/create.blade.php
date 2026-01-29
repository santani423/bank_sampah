@extends('layouts.template')

@section('title', 'Nasabah')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Form Tambah Nasabah</h3>
            <h6 class="op-7 mb-2">
                Di halaman ini Anda dapat menambah nasabah baru.
            </h6>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Informasi Nasabah</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('petugas.rekanan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jenis_badan_id">Jenis Badan <span class="text-danger">*</span></label>
                                    <select name="jenis_badan_id" id="jenis_badan_id"
                                        class="form-control @error('jenis_badan_id') is-invalid @enderror" required>
                                        <option value="">Pilih Jenis Badan</option>
                                        @foreach ($jenisBadans as $jenisBadan)
                                            <option value="{{ $jenisBadan->id }}"
                                                {{ old('jenis_badan_id') == $jenisBadan->id ? 'selected' : '' }}>
                                                {{ $jenisBadan->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('jenis_badan_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="nama_badan">Nama Badan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_badan') is-invalid @enderror"
                                        id="nama_badan" name="nama_badan" value="{{ old('nama_badan') }}" required>
                                    @error('nama_badan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="npwp">NPWP</label>
                                    <input type="text" class="form-control @error('npwp') is-invalid @enderror"
                                        id="npwp" name="npwp" value="{{ old('npwp') }}">
                                    @error('npwp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="nib">NIB</label>
                                    <input type="text" class="form-control @error('nib') is-invalid @enderror"
                                        id="nib" name="nib" value="{{ old('nib') }}">
                                    @error('nib')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="username">Username <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror"
                                        id="username" name="username" value="{{ old('username') }}" required>
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="no_hp">Nomor Telepon <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('no_hp') is-invalid @enderror"
                                        id="no_hp" name="no_hp" value="{{ old('no_hp') }}" pattern="62[0-9]{8,15}"
                                        title="Nomor HP harus diawali 62 dan hanya angka" required>
                                    @error('no_hp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="alamat_lengkap">Alamat Lengkap <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('alamat_lengkap') is-invalid @enderror" id="alamat_lengkap" name="alamat_lengkap"
                                        rows="3">{{ old('alamat_lengkap') }}</textarea>
                                    @error('alamat_lengkap')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">

                                    <x-select.select-cabang name="cabang_id" />

                                </div>
                                <div class="form-group">
                                    <label for="foto">Foto</label>
                                    <input type="file" class="form-control @error('foto') is-invalid @enderror"
                                        id="foto" name="foto">
                                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah foto</small>
                                    @error('foto')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status"
                                        class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif
                                        </option>
                                        <option value="tidak_aktif"
                                            {{ old('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('petugas.rekanan.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    @endsection

    @push('scripts')
        <!-- JS Libraies -->

        <!-- Page Specific JS File -->
    @endpush

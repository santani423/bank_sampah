@extends('layouts.template')

@section('title', 'Tambah Customer')

@push('style')
    <style>
        /* Semua teks input tetap hitam */
        input, textarea, select {
            color: #000 !important;
        }
    </style>
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Tambah Customer</h3>
            <h6 class="op-7 mb-2">Masukkan data customer baru.</h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <a href="{{ route('admin.gudangs.index') }}" class="btn btn-secondary btn-round">Kembali ke List Customer</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
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

                    <form action="{{ route('admin.gudangs.store') }}" method="POST">
                        @csrf


                        <div class="mb-3">
                            <label for="kode_gudang" class="form-label">Kode Gudang</label>
                            <input type="text" class="form-control" id="kode_gudang" name="kode_gudang" value="{{ old('kode_gudang', $newKode ?? '') }}" required maxlength="20" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="cabang_id" class="form-label">Cabang</label>
                            <select class="form-select" id="cabang_id" name="cabang_id">
                                <option value="">-- Pilih Cabang --</option>
                                @foreach($cabangs as $cabang)
                                    <option value="{{ $cabang->id }}" {{ old('cabang_id') == $cabang->id ? 'selected' : '' }}>{{ $cabang->nama_cabang ?? $cabang->id }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="nama_gudang" class="form-label">Nama Gudang</label>
                            <input type="text" class="form-control" id="nama_gudang" name="nama_gudang" value="{{ old('nama_gudang') }}" required maxlength="100">
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="kota" class="form-label">Kota</label>
                            <input type="text" class="form-control" id="kota" name="kota" value="{{ old('kota') }}" maxlength="100">
                        </div>

                        <div class="mb-3">
                            <label for="provinsi" class="form-label">Provinsi</label>
                            <input type="text" class="form-control" id="provinsi" name="provinsi" value="{{ old('provinsi') }}" maxlength="100">
                        </div>

                        <div class="mb-3">
                            <label for="kode_pos" class="form-label">Kode Pos</label>
                            <input type="text" class="form-control" id="kode_pos" name="kode_pos" value="{{ old('kode_pos') }}" maxlength="10">
                        </div>

                        <div class="mb-3">
                            <label for="telepon" class="form-label">Telepon</label>
                            <input type="text" class="form-control" id="telepon" name="telepon" value="{{ old('telepon') }}" maxlength="20">
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Tambah Customer</button>
                        <a href="{{ route('admin.gudangs.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

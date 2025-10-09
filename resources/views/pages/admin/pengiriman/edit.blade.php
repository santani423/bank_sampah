@extends('layouts.template')

@section('title', 'Edit Pengiriman Sampah')

@section('style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-5-theme/1.2.0/select2-bootstrap.min.css" rel="stylesheet">
@endsection

@section('main')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">Edit Pengiriman Sampah</h3>
        <h6 class="op-7 mb-2">
            Ubah detail pengiriman sampah ke pengepul.
        </h6>
    </div>
</div>

<div class="row">
    <div class="col-12 mb-5">

        {{-- Alert Error Validation --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Terjadi kesalahan saat pengisian formulir:</strong>
                <ul class="mt-2 mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('petugas.pengiriman.update', $pengiriman->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card mb-5">
                <div class="card-body">

                    <div class="form-group mb-3">
                        <label>Kode Pengiriman</label>
                        <input class="form-control" type="text" name="kode_pengiriman"
                            value="{{ $pengiriman->kode_pengiriman }}" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label>Pilih Cabang</label>
                        <select name="cabang_id" class="form-control @error('cabang_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Cabang --</option>
                            @foreach ($cabang as $cbg)
                                <option value="{{ $cbg->id }}" {{ $pengiriman->cabang_id == $cbg->id ? 'selected' : '' }}>
                                    {{ $cbg->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                        @error('cabang_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>Pilih Gudang</label>
                        <select name="gudang_id" class="form-control @error('gudang_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Gudang --</option>
                            @foreach ($gudang as $gdg)
                                <option value="{{ $gdg->id }}" {{ $pengiriman->gudang_id == $gdg->id ? 'selected' : '' }}>
                                    {{ $gdg->nama_gudang }}
                                </option>
                            @endforeach
                        </select>
                        @error('gudang_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>Tanggal Pengiriman</label>
                        <input type="date" name="tanggal_pengiriman"
                            value="{{ old('tanggal_pengiriman', $pengiriman->tanggal_pengiriman) }}"
                            class="form-control @error('tanggal_pengiriman') is-invalid @enderror" required>
                        @error('tanggal_pengiriman')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- DETAIL PENGIRIMAN --}}
            <div class="card">
                <div class="card-body">

                    <div class="form-group">
                        <label>Detail Pengiriman</label>
                        <table class="table table-bordered" id="pengiriman-detail-table">
                            <thead>
                                <tr>
                                    <th>Jenis Sampah</th>
                                    <th>Berat (Kg)</th>
                                    <th>Stok Tersedia (Kg)</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="pengiriman-details">
                                @foreach ($pengiriman->detailPengiriman as $detail)
                                    <tr>
                                        <td>
                                            <select name="sampah_id[]" class="form-control sampah-select" required>
                                                <option value="">-- Pilih Sampah --</option>
                                                @foreach ($stokSampah as $sampah)
                                                    <option value="{{ $sampah->id }}" data-stok="{{ $sampah->stok }}"
                                                        {{ $detail->sampah_id == $sampah->id ? 'selected' : '' }}>
                                                        {{ $sampah->nama_sampah }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="berat_kg[]" class="form-control berat-input"
                                                value="{{ $detail->berat_kg }}" placeholder="Berat (kg)" required>
                                        </td>
                                        <td class="stok-tersedia">
                                            {{ $stokSampah->firstWhere('id', $detail->sampah_id)->stok ?? 0 }} kg
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-success" id="add-row">Tambah Jenis Sampah</button>
                    </div>

                    <div class="card-footer text-right mt-4">
                        <button type="submit" class="btn btn-primary">Perbarui Pengiriman</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Tambahkan baris baru
        $('#add-row').on('click', function() {
            let newRow = `
                <tr>
                    <td>
                        <select name="sampah_id[]" class="form-control sampah-select" required>
                            <option value="">-- Pilih Sampah --</option>
                            @foreach ($stokSampah as $sampah)
                                <option value="{{ $sampah->id }}" data-stok="{{ $sampah->stok }}">
                                    {{ $sampah->nama_sampah }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" name="berat_kg[]" class="form-control berat-input" placeholder="Berat (kg)" required></td>
                    <td class="stok-tersedia"></td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button>
                    </td>
                </tr>`;
            $('#pengiriman-details').append(newRow);
        });

        // Hapus baris
        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
        });

        // Menampilkan stok saat memilih jenis sampah
        $(document).on('change', 'select[name="sampah_id[]"]', function() {
            let selectedValue = $(this).val();
            let stokTersedia = $(this).find(':selected').data('stok') || 0;
            $(this).closest('tr').find('.stok-tersedia').text(`${stokTersedia} kg`);
        });

        // Validasi berat terhadap stok
        $(document).on('input', '.berat-input', function() {
            let beratInput = parseFloat($(this).val()) || 0;
            let stokTersedia = parseFloat($(this).closest('tr').find('.stok-tersedia').text()) || 0;

            if (beratInput > stokTersedia) {
                alert('Berat tidak boleh melebihi stok yang tersedia!');
                $(this).val('');
            }
        });
    });
</script>
@endsection

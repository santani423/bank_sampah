@extends('layouts.template')

@section('title', 'Pengiriman Sampah')

@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-5-theme/1.2.0/select2-bootstrap.min.css"
        rel="stylesheet">
    <style>
        .preview-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border: 2px solid #ddd;
            border-radius: 10px;
            margin-top: 5px;
        }
    </style>
@endsection

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Formulir Pengiriman Sampah</h3>
            <h6 class="op-7 mb-2">
                Isi formulir di bawah untuk mencatat pengiriman sampah ke pengepul.
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

            <form action="{{ route('petugas.pengiriman.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card mb-5">
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label>Kode Pengiriman</label>
                            <input class="form-control" type="text" name="kode_pengiriman" value="{{ $kodePengiriman }}"
                                readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label>Pilih Cabang</label>
                            <select name="cabang_id" class="form-control @error('cabang_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Cabang --</option>
                                @foreach ($cabang as $cbg)
                                    <option value="{{ $cbg->id }}"
                                        {{ old('cabang_id') == $cbg->id ? 'selected' : '' }}>
                                        {{ $cbg->nama_cabang }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cabang_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label>Pilih Customer</label>
                            <select name="gudang_id" class="form-control @error('gudang_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Customer --</option>
                                @foreach ($gudang as $gdg)
                                    <option value="{{ $gdg->id }}"
                                        {{ old('gudang_id') == $gdg->id ? 'selected' : '' }}>
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
                                value="{{ old('tanggal_pengiriman', date('Y-m-d')) }}"
                                class="form-control @error('tanggal_pengiriman') is-invalid @enderror" required>
                            @error('tanggal_pengiriman')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Upload File Pengiriman --}}
                        <h5 class="mt-4 mb-3">Upload Dokumen Pengiriman</h5>
                        <div class="row">
                            @foreach ($refUpladPengiriman as $ref)
                            <input type="hidden" name="ref_file_id[]" value="{{$ref->id}}" >
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ $ref->nama_file }}
                                        @if ($ref->wajib)
                                            <span class="text-danger">*</span>
                                        @endif
                                    </label>
                                    <input type="file" name="file_upload[{{ $ref->id }}]"
                                        class="form-control file-input" accept="image/*"
                                        {{ $ref->wajib ? 'required' : '' }}>
                                    <img id="preview-{{ $ref->id }}" class="preview-img d-none" alt="Preview">
                                    @if ($ref->deskripsi)
                                        <small class="form-text text-muted">{{ $ref->deskripsi }}</small>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- Catatan --}}
                        <div class="form-group mt-4">
                            <label>Catatan Pengiriman (Opsional)</label>
                            <textarea name="catatan" class="form-control" rows="3" placeholder="Tambahkan catatan jika diperlukan...">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Detail Pengiriman --}}
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
                                        <td>
                                            <input type="number" name="berat_kg[]" class="form-control berat-input"
                                                placeholder="Berat (kg)" required>
                                        </td>
                                        <td class="stok-tersedia"></td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm remove-row"
                                                title="Hapus baris">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M2.5 1a1 1 0 0 1 1-1h9a1 1 0 0 1 1 1H15a.5.5 0 0 1 0 1h-1v11a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2H1a.5.5 0 0 1 0-1h1.5Zm3 3.5a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 1 0v-7a.5.5 0 0 0-.5-.5Zm3 0a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 1 0v-7a.5.5 0 0 0-.5-.5Zm3 0a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 1 0v-7a.5.5 0 0 0-.5-.5Z" />
                                                </svg>
                                            </button>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-success" id="add-row">
                                <i class="bi bi-plus-circle"></i> Tambah Jenis Sampah
                            </button>
                        </div>

                        <div class="card-footer text-right mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Lakukan Pengiriman
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Success Popup --}}
            @if (session('success'))
                <script>
                    alert('{{ session('success') }}');
                    window.open("{{ route('admin.pengiriman.print', session('pengiriman_id')) }}", '_blank');
                </script>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Preview gambar upload
        document.querySelectorAll('.file-input').forEach(input => {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                const preview = document.getElementById(`preview-${this.name.match(/\d+/)[0]}`);
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.classList.remove('d-none');
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.src = '';
                    preview.classList.add('d-none');
                }
            });
        });

        // Dynamic table JS
        $(document).ready(function() {
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
                        <button type="button" class="btn btn-danger btn-sm remove-row">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M2.5 1a1 1 0 0 1 1-1h9a1 1 0 0 1 1 1H15a.5.5 0 0 1 0 1h-1v11a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2H1a.5.5 0 0 1 0-1h1.5Zm3 3.5a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 1 0v-7a.5.5 0 0 0-.5-.5Zm3 0a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 1 0v-7a.5.5 0 0 0-.5-.5Zm3 0a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 1 0v-7a.5.5 0 0 0-.5-.5Z" />
                                                </svg>
                    </td>
                </tr>`;
                $('#pengiriman-details').append(newRow);
            });

            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
            });

            $(document).on('change', 'select[name="sampah_id[]"]', function() {
                let selectedValue = $(this).val();
                let isDuplicate = false;
                $('select[name="sampah_id[]"]').not(this).each(function() {
                    if ($(this).val() === selectedValue) {
                        isDuplicate = true;
                        return false;
                    }
                });
                if (isDuplicate) {
                    alert('Jenis sampah ini sudah dipilih!');
                    $(this).val('');
                } else {
                    let stokTersedia = $(this).find(':selected').data('stok') || 0;
                    $(this).closest('tr').find('.stok-tersedia').text(`${stokTersedia} kg`);
                }
            });

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

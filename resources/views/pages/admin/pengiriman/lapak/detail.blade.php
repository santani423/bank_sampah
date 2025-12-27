@extends('layouts.template')

@section('title', 'Detail Pengiriman Lapak')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Detail Pengiriman Lapak</h3>
        </div>
        <div class="ms-md-auto py-2 py-md-0 d-flex gap-2">
            <a href="{{ route('admin.pengiriman.lapak') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i>
                Kembali</a>
            <a href="{{ route('pdf.invoic.kirim-sampah-lapak', $pengiriman->kode_pengiriman ?? 0) }}" target="_blank"
                class="btn btn-primary">
                <i class="bi bi-file-earmark-text"></i> Invoice
            </a>
        </div>
    </div>

    <div class="card shadow mb-4 border-0">
        <div class="card-body">
            <div class="row mb-3 g-3">
                <div class="col-md-4">
                    <div class="info-box p-3 h-100 border rounded bg-light">
                        <div class="mb-2"><span class="badge bg-info text-dark">Kode Pengiriman</span></div>
                        <div class="fw-bold fs-5">{{ $pengiriman->kode_pengiriman ?? '-' }}</div>
                        <div class="text-muted small">Tanggal: {{ $pengiriman->tanggal_pengiriman ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box p-3 h-100 border rounded bg-light">
                        <div class="mb-2"><span class="badge bg-success">Collaction Center</span></div>
                        <div class="fw-bold">{{ $pengiriman->gudang->cabang->nama_cabang ?? '-' }}</div>
                        <div class="text-muted small">Gudang: {{ $pengiriman->gudang->nama_gudang ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box p-3 h-100 border rounded bg-light">
                        <div class="mb-2"><span class="badge bg-secondary">Petugas</span></div>
                        <div class="fw-bold">{{ $pengiriman->petugas->nama ?? '-' }}</div>
                        <div class="text-muted small">Driver: {{ $pengiriman->driver ?? '-' }}<br>HP:
                            {{ $pengiriman->driver_hp ?? '-' }}</div>
                    </div>
                </div>
            </div>
            <div class="row mb-3 g-3">
                <div class="col-md-6">
                    <div class="info-box p-3 h-100 border rounded bg-light">
                        <div class="mb-2"><span class="badge bg-dark">Kendaraan</span></div>
                        <div class="fw-bold">{{ $pengiriman->plat_nomor ?? '-' }}</div>
                        @if (!empty($pengiriman->foto_plat_nomor))
                            <a href="{{ asset('storage/' . $pengiriman->foto_plat_nomor) }}" data-lightbox="kendaraan"
                                data-title="Foto Plat Nomor">
                                <img src="{{ asset('storage/' . $pengiriman->foto_plat_nomor) }}" alt="foto_plat_nomor"
                                    class="img-fluid img-preview" style="max-width: 200px; cursor: zoom-in;">
                            </a>
                        @else
                            <div class="text-muted small">Tidak ada foto plat nomor</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-box p-3 h-100 border rounded bg-light">
                        <div class="mb-2"><span class="badge bg-dark">Muatan</span></div>
                        @if (!empty($pengiriman->foto_muatan))
                            <a href="{{ asset('storage/' . $pengiriman->foto_muatan) }}" data-lightbox="muatan"
                                data-title="Foto Muatan">
                                <img src="{{ asset('storage/' . $pengiriman->foto_muatan) }}" alt="foto_muatan"
                                    class="img-fluid img-preview" style="max-width: 200px; cursor: zoom-in;">
                            </a>
                        @else
                            <div class="text-muted small">Tidak ada foto muatan</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach ($pengiriman->detailPengirimanLapaks ?? [] as $index => $detailPengirimanLapaks)
        <div class="card border-0 shadow bg-white">
            <div class="card-header bg-gradient bg-primary">
                <h6 class="mb-0">
                    <i class="bi bi-trash"></i>
                    <span style="color: #fff;">{{ $detailPengirimanLapaks->transaksiLapak->kode_transaksi ?? '-' }}</span>
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Sampah</th>
                                <th>Berat Awal (kg)</th>
                                <th>Berat Terupdate (kg)</th>
                                <th>Harga per Kg</th>
                                <th>Subtotal</th>
                                <th>Susut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detailPengirimanLapaks->transaksiLapak->detailTransaksiLapak ?? [] as $idx => $item)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td>{{ $item->sampah->nama_sampah ?? '-' }}</td>
                                    <td>{{ number_format($item->berat_kg, 2, ',', '.') }}</td>
                                    <td>
                                        <input type="number" min="0" step="0.01"
                                            class="form-control form-control-sm berat-terupdate-input"
                                            name="berat_terupdate[{{ $detailPengirimanLapaks->id }}][{{ $item->id }}]"
                                            value="{{ $item->berat_terupdate ?? $item->berat_kg }}"
                                            data-berat-awal="{{ $item->berat_kg }}" data-harga="{{ $item->harga_per_kg }}"
                                            data-target-susut="susut-{{ $detailPengirimanLapaks->id }}-{{ $item->id }}"
                                            data-target-subtotal="subtotal-{{ $detailPengirimanLapaks->id }}-{{ $item->id }}">
                                    </td>
                                    <td>Rp {{ number_format($item->harga_per_kg, 0, ',', '.') }}</td>
                                    <td>
                                        <span id="subtotal-{{ $detailPengirimanLapaks->id }}-{{ $item->id }}">
                                            Rp
                                            {{ number_format(($item->berat_terupdate ?? $item->berat_kg) * $item->harga_per_kg, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span id="susut-{{ $detailPengirimanLapaks->id }}-{{ $item->id }}">
                                            {{ number_format($item->berat_kg - ($item->berat_terupdate ?? $item->berat_kg), 2, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <!-- tfoot total dihapus sesuai permintaan -->
                    </table>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Form Upload Sampah -->
    <div class="card mt-4 mb-5">
        <div class="card-header bg-primary text-white">
            <strong>Upload File Sampah</strong>
        </div>
        <div class="card-body">
            <form id="form-upload-sampah" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="file_sampah" class="form-label">Pilih File Sampah (CSV, XLSX, JPG, PNG, PDF)</label>
                    <input type="file" class="form-control" id="file_sampah" name="file_sampah"
                        accept=".csv,.xlsx,.jpg,.jpeg,.png,.pdf" required>
                </div>
                <button type="submit" class="btn btn-success"><i class="bi bi-upload"></i> Upload</button>
            </form>
            <div id="upload-feedback" class="mt-2"></div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // AJAX upload file sampah
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('form-upload-sampah');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    var formData = new FormData(form);
                    var feedback = document.getElementById('upload-feedback');
                    feedback.innerHTML = '<span class="text-info">Uploading...</span>';
                    fetch("{{ route('api.lapak.penerimaan-sampah-customer', $pengiriman->id ?? 0) }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value ||
                                    '{{ csrf_token() }}'
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                feedback.innerHTML = '<span class="text-success">' + (data.message ||
                                    'Upload berhasil!') + '</span>';
                                form.reset();
                            } else {
                                feedback.innerHTML = '<span class="text-danger">' + (data.message ||
                                    'Upload gagal!') + '</span>';
                            }
                        })
                        .catch(error => {
                            feedback.innerHTML =
                                '<span class="text-danger">Terjadi kesalahan saat upload.</span>';
                        });
                });
            }
        });
        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('.berat-terupdate-input').forEach(function(input) {
                input.addEventListener('input', function() {
                    const beratAwal = parseFloat(this.getAttribute('data-berat-awal')) || 0;
                    const harga = parseFloat(this.getAttribute('data-harga')) || 0;
                    const beratTerupdate = parseFloat(this.value) || 0;
                    const susut = beratAwal - beratTerupdate;
                    const subtotal = beratTerupdate * harga;
                    const targetSusut = document.getElementById(this.getAttribute(
                        'data-target-susut'));
                    const targetSubtotal = document.getElementById(this.getAttribute(
                        'data-target-subtotal'));
                    if (targetSusut) {
                        targetSusut.textContent = !isNaN(susut) ? susut.toLocaleString('id-ID', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }) : '-';
                    }
                    if (targetSubtotal) {
                        targetSubtotal.textContent = !isNaN(subtotal) ? 'Rp ' + subtotal
                            .toLocaleString('id-ID', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }) : '-';
                    }
                    // Update summary total
                    const table = this.closest('table');
                    if (table) {
                        updateSummary(table);
                    }
                });
            });
        });
    </script>
    <!-- JS Libraies -->
    <!-- Lightbox2 CSS & JS CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
    <style>
        .card-header.bg-gradient {
            background: linear-gradient(90deg, #007bff 0%, #00c6ff 100%);
        }

        .badge {
            font-size: 0.95em;
            padding: 0.5em 0.8em;
        }

        .info-box {
            min-height: 120px;
        }

        .img-preview {
            transition: box-shadow 0.2s;
        }

        .img-preview:hover {
            box-shadow: 0 0 0 4px #007bff44;
        }

        /* Setiap item/baris data tabel warna hitam */
        .table tbody tr td {
            color: #000 !important;
        }
    </style>
@endpush

@extends('layouts.template')

@section('title', 'Detail Pengiriman Lapak')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <form id="form-upload-sampah" enctype="multipart/form-data">
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
                    <div class="col-md-3">
                        <div class="info-box p-3 h-100 border rounded bg-light">
                            <div class="mb-2"><span class="badge bg-info text-dark">Lapak</span></div>
                            <div class="fw-bold fs-5">{{ $pengiriman->lapak->nama_lapak ?? '-' }}</div>
                            <div class="mb-1">
                                <span class="fw-bold">No Rekening:</span>
                                <span id="noRek"
                                    class="text-muted small">{{ $pengiriman->lapak->nomor_rekening ?? '-' }}</span>
                                <button type="button" class="btn btn-sm btn-primary ms-2" onclick="copyRekening()"><i
                                        class="bi bi-clipboard"></i> Copy</button>
                            </div>
                            <div id="alertCopy" class="alert alert-success alert-dismissible fade" role="alert"
                                style="display:none;position:absolute;z-index:999;top:10px;right:10px;">
                                No rekening berhasil disalin!
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            <div class="mb-1">
                                <span class="fw-bold">Atas Nama:</span>
                                <span class="text-muted small">{{ $pengiriman->lapak->nama_rekening ?? '-' }}</span>
                            </div>
                            <div class="mb-1">
                                <span class="fw-bold">Bank:</span>
                                <span class="text-muted small">{{ $pengiriman->lapak->nama_bank ?? '-' }}</span>
                            </div>
                            <script>
                                function copyRekening() {
                                    var noRek = document.getElementById('noRek').innerText;
                                    navigator.clipboard.writeText(noRek).then(function() {
                                        var alertBox = document.getElementById('alertCopy');
                                        alertBox.style.display = 'block';
                                        setTimeout(function() {
                                            alertBox.classList.add('show');
                                        }, 100);
                                        setTimeout(function() {
                                            alertBox.classList.remove('show');
                                            setTimeout(function() {
                                                alertBox.style.display = 'none';
                                            }, 500);
                                        }, 2000);
                                    });
                                }
                            </script>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box p-3 h-100 border rounded bg-light">
                            <div class="mb-2"><span class="badge bg-info text-dark">Kode Pengiriman</span></div>
                            <div class="fw-bold fs-5">{{ $pengiriman->kode_pengiriman ?? '-' }}</div>
                            <div class="text-muted small">Tanggal: {{ $pengiriman->tanggal_pengiriman ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box p-3 h-100 border rounded bg-light">
                            <div class="mb-2"><span class="badge bg-success">Collaction Center</span></div>
                            <div class="fw-bold">{{ $pengiriman->gudang->cabang->nama_cabang ?? '-' }}</div>
                            <div class="text-muted small">Gudang: {{ $pengiriman->gudang->nama_gudang ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
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
                        <span
                            style="color: #fff;">{{ $detailPengirimanLapaks->transaksiLapak->kode_transaksi ?? '-' }}</span>
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
                                        <td>{{ number_format($item->berat_terupdate ?? $item->berat_kg, 2, ',', '.') }}
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

        <!-- Summary Sampah & Total Subtotal -->
        <div class="card border-0 shadow bg-white mt-4 mb-4">
            <div class="card-header bg-gradient bg-info">
                <h6 class="mb-0"><i class="bi bi-list-ul"></i> Summary Sampah</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Sampah</th>
                                <th>Total Berat (kg)</th>
                                <th>Total Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $summarySampah = [];
                                $grandTotal = 0;
                                foreach ($pengiriman->detailPengirimanLapaks ?? [] as $detailPengirimanLapaks) {
                                    foreach (
                                        $detailPengirimanLapaks->transaksiLapak->detailTransaksiLapak ?? []
                                        as $item
                                    ) {
                                        $nama = $item->sampah->nama_sampah ?? '-';
                                        $berat = $item->berat_terupdate ?? $item->berat_kg;
                                        $subtotal = $berat * $item->harga_per_kg;
                                        $grandTotal += $subtotal;
                                        if (!isset($summarySampah[$nama])) {
                                            $summarySampah[$nama] = ['berat' => 0, 'subtotal' => 0];
                                        }
                                        $summarySampah[$nama]['berat'] += $berat;
                                        $summarySampah[$nama]['subtotal'] += $subtotal;
                                    }
                                }
                                $no = 1;
                            @endphp
                            @foreach ($summarySampah as $nama => $data)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $nama }}</td>
                                    <td>{{ number_format($data['berat'], 2, ',', '.') }}</td>
                                    <td>Rp {{ number_format($data['subtotal'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Grand Total</th>
                                <th id="grand-total">Rp {{ number_format($grandTotal, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Form Upload Sampah -->
        <div class="card mt-4 mb-5">
            <div class="card-header bg-primary text-white">
                <strong>Pembayaran Pengiriman</strong>
            </div>
            <div class="card-body">
                @csrf
                <input type="hidden" name="kode_pengiriman" value="{{ $kode ?? '' }}">
                <div class="mb-3">
                    <label for="jenis_bayar" class="form-label">Pilih Jenis Pembayaran</label>
                    <select class="form-select" id="jenis_bayar" name="jenis_bayar" required>
                        <option value="">-- Pilih Jenis Pembayaran --</option>
                        <option value="potong_saldo">Potong Saldo</option>
                        <option value="transfer">Transfer</option>
                    </select>
                </div>
                <div class="mb-3 d-none" id="bukti_transfer_group">
                    <label for="bukti_transfer" class="form-label">Upload Bukti Transfer</label>
                    <input type="file" class="form-control" id="bukti_transfer" name="bukti_transfer"
                        accept=".jpg,.jpeg,.png,.pdf">
                    <div id="preview_bukti_transfer" class="mt-2"></div>
                </div>

                <div class="mb-3">
                    <label for="catatan_sampah" class="form-label">Catatan</label>
                    <textarea class="form-control" id="catatan_sampah" name="catatan_sampah" rows="3"
                        placeholder="Masukkan keterangan tambahan (opsional)"></textarea>
                </div>
                <button type="submit" class="btn btn-success" id="btn-upload"><span id="btn-upload-text"><i
                            class="bi bi-save"></i> Simpan</span> <span id="btn-upload-spinner"
                        class="spinner-border spinner-border-sm d-none" role="status"
                        aria-hidden="true"></span></button>
                <div id="upload-feedback" class="mt-2"></div>
            </div>
        </div>
    </form>

@endsection

@push('scripts')
    <script>
        // Jenis bayar logic
        document.addEventListener('DOMContentLoaded', function() {
            var jenisBayar = document.getElementById('jenis_bayar');
            var buktiGroup = document.getElementById('bukti_transfer_group');
            var buktiInput = document.getElementById('bukti_transfer');
            var previewBukti = document.getElementById('preview_bukti_transfer');
            if (jenisBayar) {
                jenisBayar.addEventListener('change', function() {
                    if (this.value === 'transfer') {
                        buktiGroup.classList.remove('d-none');
                        buktiInput.required = true;
                    } else {
                        buktiGroup.classList.add('d-none');
                        buktiInput.required = false;
                        previewBukti.innerHTML = '';
                        buktiInput.value = '';
                    }
                });
            }
            if (buktiInput && previewBukti) {
                buktiInput.addEventListener('change', function(e) {
                    previewBukti.innerHTML = '';
                    var file = e.target.files[0];
                    if (file && file.type.match('image.*')) {
                        var reader = new FileReader();
                        reader.onload = function(evt) {
                            previewBukti.innerHTML = '<img src="' + evt.target.result +
                                '" class="img-thumbnail" style="max-width:200px;">';
                        };
                        reader.readAsDataURL(file);
                    } else if (file) {
                        previewBukti.innerHTML = '<span class="text-secondary">File: ' + file.name +
                            '</span>';
                    }
                });
            }
        });
        // AJAX upload file sampah
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('form-upload-sampah');
            if (form) {
                // Preview gambar
                var fileInput = document.getElementById('file_sampah');
                var preview = document.getElementById('preview_sampah');
                var btnUpload = document.getElementById('btn-upload');
                var btnUploadText = document.getElementById('btn-upload-text');
                var btnUploadSpinner = document.getElementById('btn-upload-spinner');
                if (fileInput && preview) {
                    fileInput.addEventListener('change', function(e) {
                        preview.innerHTML = '';
                        var file = e.target.files[0];
                        if (file && file.type.match('image.*')) {
                            var reader = new FileReader();
                            reader.onload = function(evt) {
                                preview.innerHTML = '<img src="' + evt.target.result +
                                    '" class="img-thumbnail" style="max-width:200px;">';
                            };
                            reader.readAsDataURL(file);
                        } else if (file) {
                            preview.innerHTML = '<span class="text-secondary">File: ' + file.name +
                                '</span>';
                        }
                    });
                }
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    btnUploadText.classList.add('d-none');
                    btnUploadSpinner.classList.remove('d-none');
                    var formData = new FormData(form);
                    // Ambil nilai grand total dari elemen grand-total
                    var grandTotalText = document.getElementById('grand-total')?.textContent || '';
                    // Ekstrak angka dari string (misal: "Rp 1.000.000" -> 1000000)
                    var subtotal = 0;
                    if (grandTotalText) {
                        var match = grandTotalText.match(/([\d.,]+)/);
                        if (match) {
                            subtotal = parseInt(match[1].replace(/\./g, '').replace(/,/g, '')) || 0;
                        }
                    }
                    formData.append('subtotal', subtotal);
                    var feedback = document.getElementById('upload-feedback');
                    feedback.innerHTML = '';
                    fetch("{{ route('api.lapak.bayar-sampah', $pengiriman->kode_pengiriman ?? '') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value ||
                                    '{{ csrf_token() }}'
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            btnUploadText.classList.remove('d-none');
                            btnUploadSpinner.classList.add('d-none');

                            if (data.success) {
                                console.log("btnUploadText", data.data.kode_pencairan);
                                feedback.innerHTML = '<span class="text-success">' + (data.message ||
                                    'Upload berhasil!') + '</span>';
                                // form.reset();
                                // preview.innerHTML = '';

                                setTimeout(() => {
                                    if (data.data.kode_pencairan) {
                                        window.location.href =
                                            "{{ route('admin.invoic.pencairan-lapak', '') }}/" +
                                            data.data.kode_pencairan;
                                    } else {
                                        feedback.innerHTML +=
                                            '<br><span class="text-warning">Kode pengiriman tidak ditemukan, silakan refresh halaman.</span>';
                                    }
                                }, 1500);
                            } else {
                                feedback.innerHTML = '<span class="text-danger">' + (data.message ||
                                    'Upload gagal!') + '</span>';
                            }
                        })
                        .catch(error => {
                            btnUploadText.classList.remove('d-none');
                            btnUploadSpinner.classList.add('d-none');
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

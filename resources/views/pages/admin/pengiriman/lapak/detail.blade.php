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
        <div class="ms-md-auto py-2 py-md-0">
            <a href="{{ route('admin.pengiriman.lapak') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i>
                Kembali</a>
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
    @foreach($pengiriman->detailPengirimanLapaks ?? [] as $index => $detailPengirimanLapaks)
        <div class="card border-0 shadow bg-white">
            <div class="card-header bg-gradient bg-primary text-white">
                <h6 class="mb-0"><i class="bi bi-trash"></i> {{$detailPengirimanLapaks->transaksiLapak->kode_transaksi ?? '-'}}</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Sampah</th>
                                <th>Berat (kg)</th>
                                <th>Harga per Kg</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($detailPengirimanLapaks->transaksiLapak->detailTransaksiLapak ?? [] as $idx => $item)
                            <tr></tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>{{ $item->sampah->nama_sampah ?? '-' }}</td>
                                <td>{{ number_format($item->berat_kg, 2, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->harga_per_kg, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                             
                        </tfoot>
                    </table>
                </div>
            </div>
        </div> 
        @endforeach
@endsection

@push('scripts')
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
    </style>
@endpush

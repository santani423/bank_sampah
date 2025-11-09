@extends('layouts.template')

@section('title', 'Detail Transaksi Nasabah Badan')

@push('style')
    <style>
        .info-label {
            font-weight: 600;
            color: #495057;
        }
        .info-value {
            color: #212529;
        }
        .card-transaction {
            border-left: 4px solid #667eea;
        }
        .badge-amount {
            font-size: 1.1rem;
            padding: 0.5rem 1rem;
        }
    </style>
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Detail Transaksi Nasabah Badan</h3>
            <h6 class="op-7 mb-2">Informasi lengkap transaksi setoran sampah</h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <a href="{{ route('petugas.rekanan.show', $nasabahBadan->id) }}" class="btn btn-secondary btn-round">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('petugas.rekanan.transaksi.print', [$nasabahBadan->id, $transaksi->id]) }}" class="btn btn-primary btn-round" target="_blank">
                <i class="fas fa-print"></i> Cetak Nota
            </a>
        </div>
    </div>

    <!-- Informasi Transaksi -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card card-transaction">
                <div class="card-header bg-primary text-white">
                    <h4 class="text-white mb-0">
                        <i class="fas fa-file-invoice me-2"></i>Informasi Transaksi
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="info-label">Kode Transaksi</div>
                                <div class="info-value">
                                    <h5><span class="badge badge-info">{{ $transaksi->kode_transaksi }}</span></h5>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="info-label">Tanggal Transaksi</div>
                                <div class="info-value">
                                    <i class="fas fa-calendar-alt me-2"></i>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d F Y, H:i') }} WIB
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="info-label">Nama Nasabah Badan</div>
                                <div class="info-value">
                                    <i class="fas fa-building me-2"></i>{{ $nasabahBadan->nama_badan }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="info-label">Jenis Badan</div>
                                <div class="info-value">
                                    {{ $nasabahBadan->jenisBadan->nama ?? '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="info-label">Petugas</div>
                                <div class="info-value">
                                    <i class="fas fa-user me-2"></i>{{ $transaksi->petugas->nama ?? '-' }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="info-label">Status Transaksi</div>
                                <div class="info-value">
                                    @if($transaksi->status == 'selesai')
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle me-1"></i>Selesai
                                        </span>
                                    @elseif($transaksi->status == 'pending')
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock me-1"></i>Pending
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">{{ $transaksi->status }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="info-label">Kontak</div>
                                <div class="info-value">
                                    <i class="fas fa-phone me-2"></i>{{ $nasabahBadan->no_telp ?? '-' }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="info-label">Email</div>
                                <div class="info-value">
                                    <i class="fas fa-envelope me-2"></i>{{ $nasabahBadan->email }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h5 class="text-white mb-3">
                        <i class="fas fa-money-bill-wave me-2"></i>Total Transaksi
                    </h5>
                    <h2 class="text-white mb-0">
                        <strong>Rp {{ number_format($transaksi->total_transaksi ?? $transaksi->total_harga ?? 0, 0, ',', '.') }}</strong>
                    </h2>
                    <small class="d-block mt-2" style="opacity: 0.9;">
                        Total berat: <strong>{{ number_format($transaksi->detailTransaksi->sum('berat_kg'), 2) }} kg</strong>
                    </small>
                </div>
            </div>

            @if($transaksi->keterangan)
            <div class="card">
                <div class="card-body">
                    <h5><i class="fas fa-sticky-note me-2"></i>Keterangan</h5>
                    <p class="mb-0">{{ $transaksi->keterangan }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Detail Sampah -->
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-recycle me-2"></i>Detail Sampah</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th width="5%" class="text-center">No</th>
                                    <th width="25%">Nama Sampah</th>
                                    {{-- <th width="15%">Jenis Sampah</th> --}}
                                    <th width="15%" class="text-center">Berat (kg)</th>
                                    <th width="20%" class="text-end">Harga/Kg</th>
                                    <th width="20%" class="text-end">Total Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transaksi->detailTransaksi as $index => $detail)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $detail->sampah->nama_sampah ?? '-' }}</strong>
                                        </td>
                                        {{-- <td>
                                            <span  >
                                                {{ $detail->sampah->jenis_sampah ?? '-' }}
                                            </span>
                                        </td> --}}
                                        <td class="text-center">
                                            <strong>{{ number_format($detail->berat_kg, 2) }}</strong> kg
                                        </td>
                                        <td class="text-end">
                                            Rp {{ number_format($detail->harga_per_kg, 0, ',', '.') }}
                                        </td>
                                        <td class="text-end">
                                            <strong>Rp {{ number_format($detail->harga_total, 0, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            <i class="fas fa-inbox fa-2x mb-2"></i>
                                            <p>Belum ada detail sampah</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($transaksi->detailTransaksi->count() > 0)
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="2" class="text-end"><strong>TOTAL</strong></td>
                                    <td class="text-center">
                                        <strong>{{ number_format($transaksi->detailTransaksi->sum('berat_kg'), 2) }} kg</strong>
                                    </td>
                                    <td></td>
                                    <td class="text-end">
                                        <h5 class="mb-0">
                                            <strong>Rp {{ number_format($transaksi->total_transaksi ?? $transaksi->total_harga ?? 0, 0, ',', '.') }}</strong>
                                        </h5>
                                    </td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Print styling
    window.addEventListener('beforeprint', function() {
        document.querySelector('.sidebar').style.display = 'none';
        document.querySelector('.main-header').style.display = 'none';
    });
    
    window.addEventListener('afterprint', function() {
        document.querySelector('.sidebar').style.display = 'block';
        document.querySelector('.main-header').style.display = 'block';
    });
</script>
@endpush

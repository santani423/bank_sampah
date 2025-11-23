@extends('layouts.template')

@section('title', 'Detail Lapak')

@push('style')
    <style>
        .detail-card {
            border: none;
            box-shadow: 0 0 20px rgba(0,0,0,0.08);
        }
        .detail-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        .detail-value {
            color: #212529;
            margin-bottom: 1.5rem;
        }
        .lapak-image {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .status-badge {
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }
        .info-section {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }
    </style>
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Detail Lapak</h3>
            <h6 class="op-7 mb-2">Informasi lengkap data lapak</h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <a href="{{ route('petugas.lapak.index') }}" class="btn btn-secondary btn-round">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('petugas.lapak.edit', $lapak->id) }}" class="btn btn-warning btn-round">
                <i class="bi bi-pencil-square"></i> Edit
            </a>
        </div>
    </div>

    <!-- List Data Transaksi Lapak -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card detail-card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Data Transaksi Lapak</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="transaksiLapakTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kode Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td colspan="6" class="text-center text-muted">Memuat data...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var lapakId = @json($lapak->id);
            var tableBody = document.querySelector('#transaksiLapakTable tbody');
            fetch(`/api/lapak/${lapakId}/transaksi`)
                .then(response => response.json())
                .then(data => {
                    tableBody.innerHTML = '';
                    if (data.length === 0) {
                        tableBody.innerHTML = `<tr><td colspan="6" class="text-center text-muted">Belum ada data transaksi lapak</td></tr>`;
                        return;
                    }
                    data.forEach(function(trx, idx) {
                        let statusLabel = '';
                        if (trx.approval === 'selesai' || trx.status === 'selesai') {
                            statusLabel = '<span class="badge badge-success">Selesai</span>';
                        } else if (trx.approval === 'pending' || trx.status === 'pending') {
                            statusLabel = '<span class="badge badge-warning">Pending</span>';
                        } else {
                            statusLabel = '<span class="badge badge-danger">Dibatalkan</span>';
                        }
                        let jumlah = trx.total_transaksi ? Number(trx.total_transaksi).toLocaleString('id-ID') : '-';
                        let tanggal = trx.tanggal_transaksi ? new Date(trx.tanggal_transaksi).toLocaleString('id-ID') : '-';
                        let detailUrl = `/petugas/lapak/transaksi/${trx.id}`;
                        tableBody.innerHTML += `
                            <tr>
                                <td>${idx + 1}</td>
                                <td>${trx.kode_transaksi || '-'}</td>
                                <td>${tanggal}</td>
                                <td>${jumlah}</td>
                                <td>${statusLabel}</td>
                                <td>
                                    <a href="${detailUrl}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> Detail Transaksi
                                    </a>
                                </td>
                            </tr>
                        `;
                    });
                })
                .catch(() => {
                    tableBody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Gagal memuat data transaksi</td></tr>`;
                });
        });
    </script>
@endpush

    <div class="row">
        <div class="col-md-8">
            <div class="card detail-card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Informasi Lapak</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-label">Kode Lapak</div>
                            <div class="detail-value">
                                <strong class="text-primary">{{ $lapak->kode_lapak }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">Nama Lapak</div>
                            <div class="detail-value">{{ $lapak->nama_lapak }}</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-label">Cabang</div>
                            <div class="detail-value">
                                {{ $lapak->cabang->nama_cabang ?? '-' }}<br>
                                <small class="text-muted">{{ $lapak->cabang->kode_cabang ?? '-' }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">No. Telepon</div>
                            <div class="detail-value">{{ $lapak->no_telepon ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="detail-label">Alamat</div>
                            <div class="detail-value">{{ $lapak->alamat }}</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="detail-label">Kota</div>
                            <div class="detail-value">{{ $lapak->kota ?? '-' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="detail-label">Provinsi</div>
                            <div class="detail-value">{{ $lapak->provinsi ?? '-' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="detail-label">Kode Pos</div>
                            <div class="detail-value">{{ $lapak->kode_pos ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="detail-label">Deskripsi</div>
                            <div class="detail-value">{{ $lapak->deskripsi ?? '-' }}</div>
                        </div>
                    </div>

                    @if($lapak->foto)
                        <div class="row">
                            <div class="col-12">
                                <div class="detail-label">Foto Lapak</div>
                                <img src="{{ asset('uploads/lapak/' . $lapak->foto) }}" 
                                     alt="{{ $lapak->nama_lapak }}" 
                                     class="lapak-image">
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Status Card -->
            <div class="card detail-card mb-3">
                <div class="card-header">
                    <h4 class="card-title mb-0">Status</h4>
                </div>
                <div class="card-body">
                    <div class="detail-label">Status Operasional</div>
                    <div class="mb-3">
                        @if($lapak->status == 'aktif')
                            <span class="badge badge-success status-badge" style="color: #000 !important;">
                                <i class="bi bi-check-circle-fill"></i> Aktif
                            </span>
                        @else
                            <span class="badge badge-danger status-badge" style="color: #000 !important;">
                                <i class="bi bi-x-circle-fill"></i> Tidak Aktif
                            </span>
                        @endif
                    </div>

                    <div class="detail-label">Status Approval</div>
                    <div class="mb-3">
                        @if($lapak->approval_status == 'pending')
                            <span class="badge badge-warning status-badge" style="color: #000 !important;">
                                <i class="bi bi-clock-fill"></i> Pending
                            </span>
                        @elseif($lapak->approval_status == 'approved')
                            <span class="badge badge-success status-badge" style="color: #000 !important;">
                                <i class="bi bi-check-circle-fill"></i> Approved
                            </span>
                        @else
                            <span class="badge badge-danger status-badge" style="color: #000 !important;">
                                <i class="bi bi-x-circle-fill"></i> Rejected
                            </span>
                        @endif
                    </div>

                    @if($lapak->approval_status == 'rejected' && $lapak->rejection_reason)
                        <div class="alert alert-danger">
                            <strong>Alasan Penolakan:</strong><br>
                            {{ $lapak->rejection_reason }}
                        </div>
                    @endif

                    @if($lapak->approved_at)
                        <div class="detail-label">Tanggal Approval</div>
                        <div class="detail-value">
                            {{ $lapak->approved_at->format('d F Y H:i') }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Timestamp Card -->
            <div class="card detail-card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Informasi Tambahan</h4>
                </div>
                <div class="card-body">
                    <div class="detail-label">Dibuat Pada</div>
                    <div class="detail-value">
                        <i class="bi bi-calendar3"></i> {{ $lapak->created_at->format('d F Y H:i') }}
                    </div>

                    <div class="detail-label">Terakhir Diubah</div>
                    <div class="detail-value">
                        <i class="bi bi-calendar3"></i> {{ $lapak->updated_at->format('d F Y H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('sweetalert::alert')
@endpush

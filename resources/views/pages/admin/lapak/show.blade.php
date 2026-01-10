@extends('layouts.template')

@section('title', 'Detail Lapak - Admin')

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
        .approval-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
    </style>
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Detail Lapak</h3>
            <h6 class="op-7 mb-2">Informasi lengkap dan approval lapak</h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <a href="{{ route('admin.lapak.index') }}" class="btn btn-secondary btn-round">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card detail-card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Informasi Lapak</h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

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
            <!-- Status & Approval Card -->
            <div class="card detail-card mb-3">
                <div class="card-header">
                    <h4 class="card-title mb-0">Status & Approval</h4>
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

                    <hr>

                    <div class="detail-label">Aksi Approval</div>
                    <div class="approval-actions">
                        @if($lapak->approval_status == 'pending' || $lapak->approval_status == 'rejected')
                            <form action="{{ route('admin.lapak.approve', $lapak->id) }}"
                                  method="POST"
                                  class="w-100"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menyetujui lapak ini?')">
                                @csrf
                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="bi bi-check-circle-fill"></i> Approve Lapak
                                </button>
                            </form>
                        @endif

                        @if($lapak->approval_status == 'pending' || $lapak->approval_status == 'approved')
                            <button type="button" class="btn btn-danger btn-block"
                                    data-toggle="modal"
                                    data-target="#rejectModal">
                                <i class="bi bi-x-circle-fill"></i> Reject Lapak
                            </button>
                        @endif
                    </div>
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

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.lapak.reject', $lapak->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tolak Lapak</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea name="rejection_reason"
                                      class="form-control"
                                      rows="4"
                                      required
                                      placeholder="Masukkan alasan penolakan..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak Lapak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

     <!-- List Data Transaksi Lapak (paling bawah) -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card detail-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Data Transaksi Lapak</h4>
                    <div class="d-flex">
                        <input type="text" id="searchTransaksi" class="form-control me-2"
                            placeholder="Cari kode transaksi..." style="max-width:180px;">
                        <input type="date" id="searchTanggal" class="form-control me-2" style="max-width:160px;">
                        <button id="searchBtn" class="btn btn-primary">Cari</button>
                    </div>
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
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Memuat data...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div id="paginationInfo" class="text-muted"></div>
                        <div>
                            <button id="prevPage" class="btn btn-outline-secondary btn-sm me-2">&laquo; Prev</button>
                            <button id="nextPage" class="btn btn-outline-secondary btn-sm">Next &raquo;</button>
                        </div>
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
                var searchInput = document.getElementById('searchTransaksi');
                var searchBtn = document.getElementById('searchBtn');
                var searchTanggal = document.getElementById('searchTanggal');
                var prevPageBtn = document.getElementById('prevPage');
                var nextPageBtn = document.getElementById('nextPage');
                var paginationInfo = document.getElementById('paginationInfo');
                var currentPage = 1;
                var limit = 10;
                var lastPage = 1;
                var currentSearch = '';
                var currentTanggal = '';

                function loadTransaksi(page = 1, search = '', tanggal = '') {
                    tableBody.innerHTML = `<tr><td colspan="6" class="text-center text-muted">Memuat data...</td></tr>`;
                    let url = `/api/lapak/${lapakId}/transaksi?page=${page}&limit=${limit}`;
                    if (search) url += `&search=${encodeURIComponent(search)}`;
                    if (tanggal) url += `&tanggal=${encodeURIComponent(tanggal)}`;
                    fetch(url)
                        .then(response => response.json())
                        .then(res => {
                            var data = res.data || res;
                            var total = res.total || data.length;
                            lastPage = res.last_page || Math.ceil(total / limit);
                            currentPage = res.current_page || page;
                            paginationInfo.textContent =
                                `Halaman ${currentPage} dari ${lastPage} | Total: ${total}`;
                            tableBody.innerHTML = '';
                            if (!Array.isArray(data) || data.length === 0) {
                                tableBody.innerHTML =
                                    `<tr><td colspan="6" class="text-center text-muted">Belum ada data transaksi lapak</td></tr>`;
                                return;
                            }
                            data.forEach(function(trx, idx) {
                                let statusLabel = '';
                                if (trx.approval === 'selesai' || trx.status === 'selesai') {
                                    statusLabel = '<span class="badge badge-success" style="color:#000 !important;">Selesai</span>';
                                } else if (trx.approval === 'pending' || trx.status === 'pending') {
                                    statusLabel = '<span class="badge badge-warning" style="color:#000 !important;">Pending</span>';
                                } else {
                                    statusLabel = '<span class="badge badge-danger" style="color:#000 !important;">Dibatalkan</span>';
                                }
                                let jumlah = trx.total_transaksi ? Number(trx.total_transaksi)
                                    .toLocaleString('id-ID') : '-';
                                let tanggal = trx.tanggal_transaksi ? new Date(trx.tanggal_transaksi)
                                    .toLocaleString('id-ID') : '-';
                                let detailUrl = `/admin/setor-lapak/${trx.kode_transaksi || ''}`;
                                tableBody.innerHTML += `
                                <tr>
                                    <td>${(idx + 1) + ((currentPage - 1) * limit)}</td>
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
                        .catch((err) => {
                            tableBody.innerHTML =
                                `<tr><td colspan="6" class="text-center text-danger">Gagal memuat data transaksi</td></tr>`;
                        });
                }

                searchBtn.addEventListener('click', function() {
                    currentSearch = searchInput.value;
                    currentTanggal = searchTanggal.value;
                    currentPage = 1;
                    loadTransaksi(currentPage, currentSearch, currentTanggal);
                });
                searchInput.addEventListener('keyup', function(e) {
                    if (e.key === 'Enter') {
                        currentSearch = searchInput.value;
                        currentTanggal = searchTanggal.value;
                        currentPage = 1;
                        loadTransaksi(currentPage, currentSearch, currentTanggal);
                    }
                });
                searchTanggal.addEventListener('change', function() {
                    currentTanggal = searchTanggal.value;
                    currentPage = 1;
                    loadTransaksi(currentPage, currentSearch, currentTanggal);
                });
                prevPageBtn.addEventListener('click', function() {
                    if (currentPage > 1) {
                        currentPage--;
                        loadTransaksi(currentPage, currentSearch, currentTanggal);
                    }
                });
                nextPageBtn.addEventListener('click', function() {
                    if (currentPage < lastPage) {
                        currentPage++;
                        loadTransaksi(currentPage, currentSearch, currentTanggal);
                    }
                });

                // Initial load
                loadTransaksi();
            });
        </script>
    @endpush

@endsection

@push('scripts')
    @include('sweetalert::alert')
@endpush

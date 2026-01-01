@extends('layouts.template')

@section('title', 'Pengiriman Lapak')

@push('style')
    <style>
        #loading-spinner {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
        }

        .pagination-controls button {
            margin: 0 2px;
        }
    </style>
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Pengiriman Lapak</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    {{-- ALERT --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- FILTER FORM --}}
                    <form id="filter-form">
                        <div class="row align-items-end">

                            <div class="col-md-3 mb-3">
                                <x-select.select-cabang />
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Customer</label>
                                <input type="text" class="form-control" id="customer" placeholder="Nama customer">
                            </div>

                            <div class="col-md-3 mb-3">
                                <x-select.select-status-pengiriman />
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Tanggal Pengiriman</label>
                                <input type="text" class="form-control" id="tanggal_range"
                                    placeholder="YYYY-MM-DD s/d YYYY-MM-DD" autocomplete="off">
                            </div>

                            <div class="col-md-12 mb-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Cari
                                </button>

                                <a href="{{ url()->current() }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    {{-- TABLE --}}
                    <div class="table-responsive">
                        <div id="loading-spinner">
                            <div class="spinner-border" role="status"></div>
                        </div>

                        <table class="table table-hover table-bordered table-head-bg-primary text-nowrap" id="petugas-table"
                            style="display:none;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Aksi</th>
                                    <th>Kode Pengiriman</th>
                                    <th>Tanggal</th>
                                    <th>Collation Center</th>
                                    <th>Customer</th>
                                    <th>Driver</th>
                                    <th>Driver HP</th>
                                    <th>Plat</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="petugas-tbody"></tbody>
                        </table>

                        <div id="pagination-wrapper" class="pagination-wrapper" style="display:none;">
                            <div id="pagination-info"></div>
                            <div id="pagination-controls"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        /* ===============================
               AMBIL FILTER TANGGAL
            ================================ */
        function getFilterParams() {
            const tanggalRange = document.getElementById('tanggal_range').value;
            let tanggalMulai = '';
            let tanggalSelesai = '';

            if (tanggalRange.includes('s/d')) {
                const split = tanggalRange.split('s/d');
                tanggalMulai = split[0].trim();
                tanggalSelesai = split[1].trim();
            }

            return {
                tanggal_mulai: tanggalMulai,
                tanggal_selesai: tanggalSelesai
            };
        }

        /* ===============================
           FETCH DATA API
        ================================ */
        function fetchPetugasData(page = 1) {
            const spinner = document.getElementById('loading-spinner');
            const table = document.getElementById('petugas-table');
            const pagination = document.getElementById('pagination-wrapper');

            spinner.style.display = 'block';
            table.style.display = 'none';
            pagination.style.display = 'none';

            const filters = getFilterParams();

            const params = new URLSearchParams({
                page,
                per_page: perPage,
                ...(filters.tanggal_mulai && {
                    tanggal_mulai: filters.tanggal_mulai
                }),
                ...(filters.tanggal_selesai && {
                    tanggal_selesai: filters.tanggal_selesai
                })
            });

            fetch(`/api/lapak/pengiriman?${params.toString()}`)
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        current_page = res.pagination.current_page;
                        totalPages = res.pagination.last_page;
                        renderTable(res.data, res.pagination);
                        renderPagination(res.pagination);

                        spinner.style.display = 'none';
                        table.style.display = 'table';
                        pagination.style.display = 'flex';
                    }
                })
                .catch(() => {
                    spinner.innerHTML = '<div class="alert alert-danger">Gagal memuat data</div>';
                });
        }

        /* ===============================
           RENDER TABLE
        ================================ */
        function renderTable(data, pagination) {
            const tbody = document.getElementById('petugas-tbody');
            tbody.innerHTML = '';

            if (!data.length) {
                tbody.innerHTML = `<tr><td colspan="10" class="text-center">Tidak ada data</td></tr>`;
                return;
            }

            const detailRoute = "{{ route('admin.pengiriman-lapak.detail', ':kode') }}";

            data.forEach((item, index) => {
                const no = pagination.from + index;
                const url = detailRoute.replace(':kode', item.kode_pengiriman);

                tbody.innerHTML += `
            <tr>
                <td>${no}</td>
                <td>
                    <a href="${url}" class="btn btn-sm btn-info">Detail</a>
                </td>
                <td>${item.kode_pengiriman}</td>
                <td>${item.tanggal_pengiriman}</td>
                <td>${item.gudang?.cabang?.nama_cabang ?? '-'}</td>
                <td>${item.gudang?.nama_gudang ?? '-'}</td>
                <td>${item.driver ?? '-'}</td>
                <td>${item.driver_hp ?? '-'}</td>
                <td>${item.plat_nomor ?? '-'}</td>
                <td>${item.status_pengiriman ?? '-'}</td>
            </tr>
        `;
            });
        }



        /* ===============================
           EVENT
        ================================ */
        document.getElementById('filter-form').addEventListener('submit', function(e) {
            e.preventDefault();
            fetchPetugasData(1);
        });

        document.addEventListener('DOMContentLoaded', () => {
            fetchPetugasData(1);
        });
    </script>
@endpush

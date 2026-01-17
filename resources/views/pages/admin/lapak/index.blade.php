@extends('layouts.template')

@section('title', 'Data Lapak')

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
            <h3 class="fw-bold mb-3">Data Lapak</h3>
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
                    <form method="GET" action="{{ route('admin.lapak.index') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nama Lapak</label>
                                    <input type="text" name="nama_lapak" class="form-control"
                                        placeholder="Cari nama lapak..." value="{{ request('nama_lapak') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Status Approval</label>
                                    <select name="approval_status" class="form-control">
                                        <option value="">Semua Status</option>
                                        <option value="pending"
                                            {{ request('approval_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved"
                                            {{ request('approval_status') == 'approved' ? 'selected' : '' }}>Approved
                                        </option>
                                        <option value="rejected"
                                            {{ request('approval_status') == 'rejected' ? 'selected' : '' }}>Rejected
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Cabang</label>
                                    <select name="cabang_id" class="form-control">
                                        <option value="">Semua Cabang</option>
                                        @foreach ($cabangs as $cabang)
                                            <option value="{{ $cabang->id }}"
                                                {{ request('cabang_id') == $cabang->id ? 'selected' : '' }}>
                                                {{ $cabang->nama_cabang }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fas fa-search"></i> Filter
                                        </button>
                                        <a href="{{ route('admin.lapak.index') }}" class="btn btn-secondary btn-block mt-1">
                                            <i class="fas fa-redo"></i> Reset
                                        </a>
                                    </div>
                                </div>
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
                                    <th style="width: 250px">Aksi</th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>No. Registrasi</th>
                                    <th>Cabang</th>
                                    <th>No. HP</th>
                                    <th>Saldo</th>
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

            const nama_lapak = document.getElementById('nama_lapak').value;
            const approval_status = document.getElementById('approval_status').value;
            const cabang_id = document.getElementById('cabang_id').value;


            return {
                nama_lapak: nama_lapak,
                approval_status: approval_status,
                cabang_id: cabang_id,
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

                ...(filters.nama_lapak && {
                    nama_lapak: filters.nama_lapak,
                }),
                ...(filters.approval_status && {
                    approval_status: filters.approval_status,
                }),
                ...(filters.cabang_id && {
                    cabang_id: filters.cabang_id,
                }),
            });





            fetch(`/api/admin/nasabah?${params.toString()}`)
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

            const detailRoute = "{{ route('admin.nasabah.show', ':kode') }}";
            const detailEdit = "{{ route('admin.nasabah.edit', ':kode') }}";

            data.forEach((item, index) => {
                const no = pagination.from + index;
                const url = detailRoute.replace(':kode', item.kode_pengiriman);
                const urlEdit = detailEdit.replace(':kode', item.kode_pengiriman);

                tbody.innerHTML += `
            <tr>
                <td>${no}</td>
                <td>
                     <a href="${url}" class="btn btn-sm btn-info">Detail</a>
                     <a href="${urlEdit}" class="btn btn-sm btn-primary">Edit</a>
                </td>
                <td>${item.nama_lengkap}</td> 
                <td>${item.username}</td> 
                <td>${item.no_registrasi}</td>
                <td>${item.nama_cabang}</td>
                <td>${item.no_hp}</td>
                <td>${formatRupiah(item.saldo.saldo)}</td>
                <td>${item.status}</td>
            </tr>
        `;
            });
        }

        // Fungsi format rupiah
        function formatRupiah(angka) {
            if (angka == null) return '-';
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
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

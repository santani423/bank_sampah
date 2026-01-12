@extends('layouts.template')

@section('title', 'Penarikan Saldo Nasabah')

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
            <h3 class="fw-bold mb-3">Penarikan Saldo Nasabah</h3>
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
                                <label class="form-label">Nasabah</label>
                                <input type="text" class="form-control" id="nasabah" placeholder="Nama nasabah">
                            </div>




                            <div class="col-md-12 mb-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Cari
                                </button>

                                <a href="{{ url()->current() }}" class="btn btn-secondary">
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
                                    <th>Tanggal</th>
                                    <th>Jumlah Penarikan</th>
                                    <th>Metode Penarikan</th>
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

            const nasabah = document.getElementById('nasabah').value;



            return {
                nasabah: nasabah,
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

                ...(filters.nasabah && {
                    search: filters.nasabah,
                })
            });
            fetch(`/api/nasabah/withdrawalList?${params.toString()}`)
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        current_page = res.pagination.current_page;
                        totalPages = res.pagination.last_page;
                        renderTable(res.data, res.pagination);
                        renderPagination(res.pagination);

                        spinner.style.display = 'none';
                        ""
                        table.style.display = 'table';
                        pagination.style.display = 'flex';
                    }
                })
                .catch(() => {
                    spinner.innerHTML = '<div class="alert alert-danger">Gagal memuat data</div>';
                });
        }

        // Fungsi format rupiah
        function formatRupiah(angka) {
            if (angka == null) return '-';
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
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
                console.log("item", item);
                let tanggal = '-';
                if (item?.created_at) {
                    const dateObj = new Date(item.created_at);
                    const pad = n => n.toString().padStart(2, '0');
                    tanggal =
                        `${dateObj.getFullYear()}-${pad(dateObj.getMonth()+1)}-${pad(dateObj.getDate())} ${pad(dateObj.getHours())}:${pad(dateObj.getMinutes())}`;
                }
                const jumlahRupiah = formatRupiah(item?.jumlah_pencairan);
                tbody.innerHTML += `
            <tr>
                <td>${no}</td>
                <td>
                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalSetujui"
                        >
                        Detail
                    </button> 
                </td>
                <td>${tanggal}</td>
                <td>${item?.jumlah_pencairan}</td>
                <td>${item?.metode_pencairan?.jenis_metode_penarikan?.nama}</td>
                <td>${item?.status}</td>
               
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

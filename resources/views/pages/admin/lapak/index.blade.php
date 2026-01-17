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
                    <form id="filter-form">
                        <div class="row align-items-end">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Nasabah</label>
                                <input type="text" class="form-control" id="nasabah" placeholder="Nama nasabah">
                            </div>
                            <div class="col-md-3 mb-3">
                                <x-select.select-cabang name="cabang" />
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
                                    <th>Kode Lapak</th>
                                    <th>Nama Lapak</th>
                                    <th>Collation Center</th>
                                    <th>Alamat</th>
                                    <th>Status</th>
                                    <th>Approval</th>
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
            const cabang = document.getElementById('cabang').value;



            return {
                nasabah: nasabah,
                cabang: cabang,
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
                }),
                ...(filters.cabang && {
                    cabang: filters.cabang,
                }),
            });





            fetch(`/api/lapak?${params.toString()}`)
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

            const detailRoute = "{{ route('admin.lapak.show', ':id') }}";
            const editRoute = "{{ route('admin.lapak.approve', ':id') }}";
            const deleteRoute = "{{ route('admin.lapak.destroy', ':id') }}";

            data.forEach((item, index) => {
                const no = pagination.from + index;
                const showUrl = detailRoute.replace(':id', item.id);
                const editUrl = editRoute.replace(':id', item.id);

                let approveButton = '';

                if (item.approval_status === 'pending' || item.approval_status === 'rejected') {
                    approveButton = `
            <button 
                onclick="approveItem('${editUrl}', '${item.nama_lapak}')" 
                class="btn btn-sm btn-success" 
                title="Approve"
            >
                <i class="bi bi-check-circle-fill"></i>
            </button>
        `;
                }

                tbody.innerHTML += `
        <tr>
            <td>${no}</td>
            <td>
                <a href="${showUrl}" class="btn btn-sm btn-info" title="Detail">
                    <i class="bi bi-eye"></i>
                </a>
                ${approveButton}
            </td>
            <td>${item.kode_lapak}</td>
            <td>${item.nama_lapak}</td>
            <td>${item.cabang ? item.cabang.nama_cabang : '-'}</td>
            <td>${item.alamat}</td>
            <td>${item.status}</td>
            <td>${item.approval_status}</td>
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
           DELETE FUNCTION
        ================================ */
        function approveItem(url, namaLapak) {
            if (confirm(`Apakah Anda yakin ingin menyetujui lapak "${namaLapak}"?`)) {
                fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(res => {
                        if (res.success) {
                            alert('Data berhasil disetujui');
                            fetchPetugasData(current_page);
                        } else {
                            alert('Gagal menyetujui data: ' + (res.message || 'Terjadi kesalahan'));
                        }
                    })
                    .catch(err => {
                        alert('Terjadi kesalahan saat menyetujui data');
                        console.error(err);
                    });
            }
        }

        function deleteItem(url, namaLapak) {
            if (confirm(`Apakah Anda yakin ingin menghapus lapak "${namaLapak}"?`)) {
                fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(res => {
                        if (res.success) {
                            alert('Data berhasil dihapus');
                            fetchPetugasData(current_page);
                        } else {
                            alert('Gagal menghapus data: ' + (res.message || 'Terjadi kesalahan'));
                        }
                    })
                    .catch(err => {
                        alert('Terjadi kesalahan saat menghapus data');
                        console.error(err);
                    });
            }
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

@extends('layouts.template')

@section('title', 'Petugas')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Pengiriman Lapak</h3>
        </div>
        <div class="ms-md-auto py-2 py-md-0">

        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="clearfix mb-3"></div>
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive" style="overflow-x:auto;">
                        <div id="loading-spinner">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <table class="table table-hover table-bordered table-head-bg-primary text-nowrap" id="petugas-table"
                            style="display: none;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kode Pengiriman</th>
                                    <th>Tanggal Pengiriman</th>
                                    <th>Driver</th>
                                    <th>Driver HP</th>
                                    <th>Plat Nomor</th>
                                </tr>
                            </thead>
                            <tbody id="petugas-tbody">
                                <!-- Data akan diisi oleh JavaScript -->
                            </tbody>
                        </table>

                        <div id="pagination-wrapper" class="pagination-wrapper" style="display: none;">
                            <div class="pagination-info" id="pagination-info"></div>
                            <div class="pagination-controls" id="pagination-controls"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script>
        let currentPage = 1;
        let perPage = 10;
        let totalPages = 1;

        // Fungsi untuk mengambil data dari API
        function fetchPetugasData(page = 1) {
            const loadingSpinner = document.getElementById('loading-spinner');
            const table = document.getElementById('petugas-table');
            const paginationWrapper = document.getElementById('pagination-wrapper');

            // Tampilkan loading
            loadingSpinner.style.display = 'block';
            table.style.display = 'none';
            paginationWrapper.style.display = 'none';

            fetch(`/api/lapak/pengiriman/pending?page=${page}&per_page=${perPage}`)
                .then(response => response.json())
                .then(data => {
                    console.log("data",data);
                    if (data.success) {
                        currentPage = data.pagination.current_page;
                        totalPages = data.pagination.last_page;
                        renderTable(data.data, data.pagination);
                        renderPagination(data.pagination);

                        // Sembunyikan loading dan tampilkan table
                        loadingSpinner.style.display = 'none';
                        table.style.display = 'table';
                        paginationWrapper.style.display = 'flex';
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    loadingSpinner.innerHTML = '<div class="alert alert-danger">Gagal memuat data</div>';
                });
        }

        // Fungsi untuk render tabel
        function renderTable(data, pagination) {
            const tbody = document.getElementById('petugas-tbody');
            tbody.innerHTML = '';

            console.log("data pagination",pagination);
            

            if (data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center">
                            Belum ada data pengiriman.
                        </td>
                    </tr>
                `;
                return;
            }

            data.forEach((data, index) => {
                const rowNumber = pagination.from + index;
                const row = `
                    <tr>
                        <td>${rowNumber}</td>
                        <td>${data.kode_pengiriman}</td>
                        <td>${data.tanggal_pengiriman}</td>
                        <td>${data.driver}</td>
                        <td>${data.driver_hp}</td>
                        <td>${data.plat_nomor}</td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }
    </script>
@endpush

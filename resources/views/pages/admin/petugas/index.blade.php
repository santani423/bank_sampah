@extends('layouts.template')

@section('title', 'Petugas')

@push('style')
    <!-- CSS Libraries -->
    <style>
        .pagination-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        .pagination-info {
            color: #666;
        }
        .pagination-controls {
            display: flex;
            gap: 5px;
        }
        .page-btn {
            padding: 5px 10px;
            border: 1px solid #ddd;
            background: white;
            cursor: pointer;
            border-radius: 3px;
        }
        .page-btn:hover:not(:disabled) {
            background: #f0f0f0;
        }
        .page-btn.active {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }
        .page-btn:disabled {
            cursor: not-allowed;
            opacity: 0.5;
        }
        #loading-spinner {
            text-align: center;
            padding: 20px;
        }
    </style>
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Manajem Petugas</h3>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <div class="section-header-button">
                <a href="{{ route('admin.petugas.create') }}" class="btn btn-primary btn-round">Tambah Petugas</a>
            </div>
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
                        <table class="table table-hover table-bordered table-head-bg-primary text-nowrap" id="petugas-table" style="display: none;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
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

            fetch(`/api/petugas?page=${page}&per_page=${perPage}`)
                .then(response => response.json())
                .then(data => {
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

            if (data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center">
                            Belum ada petugas.
                        </td>
                    </tr>
                `;
                return;
            }

            data.forEach((petugas, index) => {
                const rowNumber = pagination.from + index;
                const row = `
                    <tr>
                        <td>${rowNumber}</td>
                        <td>${petugas.nama}</td>
                        <td>${petugas.email}</td>
                        <td>${petugas.username}</td>
                        <td>${petugas.role.charAt(0).toUpperCase() + petugas.role.slice(1)}</td>
                        <td>
                            <form onsubmit="return confirm('Apakah Anda yakin?');"
                                action="/admin/data-petugas/${petugas.id}" method="POST">
                                <a href="/admin/data-petugas/${petugas.id}"
                                    class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <a href="/admin/data-petugas/${petugas.id}/edit"
                                    class="btn btn-sm btn-primary">
                                    <i class="fas fa-pencil-alt"></i> Edit
                                </a>
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        // Fungsi untuk render pagination
        function renderPagination(pagination) {
            const paginationInfo = document.getElementById('pagination-info');
            const paginationControls = document.getElementById('pagination-controls');

            // Info pagination
            paginationInfo.textContent = `Menampilkan ${pagination.from || 0} sampai ${pagination.to || 0} dari ${pagination.total} data`;

            // Controls pagination
            paginationControls.innerHTML = '';

            // Tombol Previous
            const prevBtn = document.createElement('button');
            prevBtn.className = 'page-btn';
            prevBtn.textContent = '« Previous';
            prevBtn.disabled = currentPage === 1;
            prevBtn.onclick = () => fetchPetugasData(currentPage - 1);
            paginationControls.appendChild(prevBtn);

            // Tombol halaman
            const startPage = Math.max(1, currentPage - 2);
            const endPage = Math.min(totalPages, currentPage + 2);

            if (startPage > 1) {
                const firstBtn = document.createElement('button');
                firstBtn.className = 'page-btn';
                firstBtn.textContent = '1';
                firstBtn.onclick = () => fetchPetugasData(1);
                paginationControls.appendChild(firstBtn);

                if (startPage > 2) {
                    const dots = document.createElement('span');
                    dots.textContent = '...';
                    dots.style.padding = '5px 10px';
                    paginationControls.appendChild(dots);
                }
            }

            for (let i = startPage; i <= endPage; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.className = 'page-btn' + (i === currentPage ? ' active' : '');
                pageBtn.textContent = i;
                pageBtn.onclick = () => fetchPetugasData(i);
                paginationControls.appendChild(pageBtn);
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    const dots = document.createElement('span');
                    dots.textContent = '...';
                    dots.style.padding = '5px 10px';
                    paginationControls.appendChild(dots);
                }

                const lastBtn = document.createElement('button');
                lastBtn.className = 'page-btn';
                lastBtn.textContent = totalPages;
                lastBtn.onclick = () => fetchPetugasData(totalPages);
                paginationControls.appendChild(lastBtn);
            }

            // Tombol Next
            const nextBtn = document.createElement('button');
            nextBtn.className = 'page-btn';
            nextBtn.textContent = 'Next »';
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.onclick = () => fetchPetugasData(currentPage + 1);
            paginationControls.appendChild(nextBtn);
        }

        // Load data saat halaman pertama kali dimuat
        document.addEventListener('DOMContentLoaded', function() {
            fetchPetugasData(1);
        });
    </script>
@endpush

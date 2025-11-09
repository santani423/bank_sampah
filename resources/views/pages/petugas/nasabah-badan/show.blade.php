@extends('layouts.template')

@section('title', 'Detail Nasabah Badan')

@push('style')
    <style>
        /* Loading overlay container */
        #detail-container { position: relative; min-height: 400px; }
        .loading-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }
        .loading-overlay .spinner {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            gap: .5rem;
        }
        .info-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.25rem;
        }
        .info-value {
            color: #212529;
            margin-bottom: 1rem;
        }
        .badge-status {
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
        }
        
        /* History Setoran Sampah Table Styles */
        #transaction-table-body tr td {
            color: #212529 !important;
            vertical-align: middle;
        }
        
        #transaction-table-body tr td ul {
            color: #212529 !important;
        }
        
        #transaction-table-body tr td ul li {
            color: #212529 !important;
        }
        
        #transaction-table-body tr td ul li small {
            color: #495057 !important;
        }
        
        #transaction-table-body tr td strong {
            color: #000000 !important;
        }
        
        .table thead th {
            color: #212529 !important;
            font-weight: 600;
        }
        
        #showing-info {
            color: #6c757d !important;
        }
        
        /* Saldo Card Styles */
        .saldo-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .saldo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
        }
        
        .saldo-card .saldo-label {
            font-size: 0.9rem;
            font-weight: 500;
            opacity: 0.9;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .saldo-card .saldo-amount {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
        }
        
        .saldo-card .saldo-icon {
            font-size: 3rem;
            opacity: 0.3;
            position: absolute;
            right: 2rem;
            top: 50%;
            transform: translateY(-50%);
        }
    </style>
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Detail Nasabah Badan</h3>
            <h6 class="op-7 mb-2">Informasi lengkap nasabah badan</h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <a href="{{ route('petugas.rekanan.index') }}" class="btn btn-secondary btn-round">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('petugas.rekanan.edit', $nasabahBadan->id) }}" class="btn btn-warning btn-round">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
    </div>

    <!-- Saldo Card - Prominent Display -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="saldo-card position-relative">
                <div class="row align-items-center">
                    <div class="col-md-10">
                        <div class="saldo-label">
                            <i class="fas fa-wallet me-2"></i>Saldo Nasabah Badan
                        </div>
                        <h2 class="saldo-amount" id="saldo-display">
                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            Memuat...
                        </h2>
                        <small class="d-block mt-2" style="opacity: 0.8;" id="saldo-updated">Terakhir diperbarui: -</small>
                    </div>
                    <div class="col-md-2 text-end d-none d-md-block">
                        <i class="fas fa-money-bill-wave saldo-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Informasi Nasabah Badan</h4>
                </div>
                <div class="card-body" id="detail-container">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <div class="mb-3">
                                <img id="nasabah-foto" src="" alt="Foto Nasabah" class="img-thumbnail" style="max-width: 250px; max-height: 250px;">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-label">Nama Badan</div>
                                    <div class="info-value" id="nama-badan">-</div>

                                    <div class="info-label">Jenis Badan</div>
                                    <div class="info-value" id="jenis-badan">-</div>

                                    <div class="info-label">NPWP</div>
                                    <div class="info-value" id="npwp">-</div>

                                    <div class="info-label">NIB</div>
                                    <div class="info-value" id="nib">-</div>

                                    <div class="info-label">Email</div>
                                    <div class="info-value" id="email">-</div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-label">Username</div>
                                    <div class="info-value" id="username">-</div>

                                    <div class="info-label">Nomor Telepon</div>
                                    <div class="info-value" id="no-telp">-</div>

                                    <div class="info-label">Status</div>
                                    <div class="info-value">
                                        <span id="status" class="badge badge-status">-</span>
                                    </div>

                                    <div class="info-label">Terdaftar Sejak</div>
                                    <div class="info-value" id="created-at">-</div>

                                    <div class="info-label">Terakhir Diperbarui</div>
                                    <div class="info-value" id="updated-at">-</div>
                                </div>

                                <div class="col-md-12">
                                    <div class="info-label">Alamat Lengkap</div>
                                    <div class="info-value" id="alamat-lengkap">-</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Loading overlay -->
                    <div id="loading-overlay" class="loading-overlay">
                        <div class="spinner">
                            <div class="spinner-border text-primary" role="status" aria-hidden="true" style="width: 3rem; height: 3rem;"></div>
                            <div class="text-muted">Memuat data...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- History Setoran Sampah -->
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>History Setoran Sampah</h4>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="start-date" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="start-date" name="start_date">
                        </div>
                        <div class="col-md-4">
                            <label for="end-date" class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="end-date" name="end_date">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <button type="button" class="btn btn-primary" id="btn-filter">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <button type="button" class="btn btn-secondary" id="btn-reset">
                                    <i class="fas fa-redo"></i> Reset
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>Detail Sampah</th>
                                    <th>Total Berat</th>
                                    <th>Total Harga</th>
                                    <th>Petugas</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="transaction-table-body">
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div id="showing-info" class="text-muted"></div>
                        <nav>
                            <ul class="pagination mb-0" id="pagination-container"></ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const nasabahId = {{ $nasabahBadan->id }};
    let currentPage = 1;
    let currentFilters = {
        start_date: '',
        end_date: ''
    };
    
    // Fetch data from API
    function fetchNasabahDetail() {
        $('#loading-overlay').show();
        
        $.ajax({
            url: `/api/nasabah-badan/${nasabahId}`,
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            },
            success: function(response) {
                if (response.success && response.data) {
                    const data = response.data;
                    
                    // Populate data
                    $('#nama-badan').text(data.nama_badan || '-');
                    $('#jenis-badan').text(data.jenis_badan ? data.jenis_badan.nama : '-');
                    $('#npwp').text(data.npwp || '-');
                    $('#nib').text(data.nib || '-');
                    $('#email').text(data.email || '-');
                    $('#username').text(data.username || '-');
                    $('#no-telp').text(data.no_telp || '-');
                    $('#alamat-lengkap').text(data.alamat_lengkap || '-');
                    
                    // Saldo - Display in prominent card
                    const saldo = data.saldo ? data.saldo.saldo : 0;
                    $('#saldo-display').html(`Rp ${formatCurrency(saldo)}`);
                    
                    // Saldo last updated
                    if (data.saldo && data.saldo.updated_at) {
                        const saldoUpdated = new Date(data.saldo.updated_at);
                        $('#saldo-updated').text(`Terakhir diperbarui: ${formatDate(saldoUpdated)}`);
                    } else {
                        $('#saldo-updated').text('Terakhir diperbarui: -');
                    }
                    
                    // Status badge
                    const statusBadge = $('#status');
                    if (data.status === 'aktif') {
                        statusBadge.removeClass('badge-danger').addClass('badge-success');
                        statusBadge.text('Aktif');
                    } else {
                        statusBadge.removeClass('badge-success').addClass('badge-danger');
                        statusBadge.text('Tidak Aktif');
                    }
                    
                    // Foto
                    const fotoUrl = data.foto 
                        ? `/storage/nasabah-badan/${data.foto}` 
                        : '/storage/nasabah-badan/profil.png';
                    $('#nasabah-foto').attr('src', fotoUrl);
                    
                    // Dates
                    const createdAt = new Date(data.created_at);
                    const updatedAt = new Date(data.updated_at);
                    $('#created-at').text(formatDate(createdAt));
                    $('#updated-at').text(formatDate(updatedAt));
                }
            },
            error: function(xhr) {
                alert('Gagal memuat data. Silakan refresh halaman.');
                console.error('Error:', xhr);
            },
            complete: function() {
                $('#loading-overlay').fadeOut(300);
            }
        });
    }
    
    // Fetch transaction history
    function fetchTransactionHistory(page = 1) {
        const params = {
            page: page,
            per_page: 10
        };
        
        if (currentFilters.start_date) {
            params.start_date = currentFilters.start_date;
        }
        
        if (currentFilters.end_date) {
            params.end_date = currentFilters.end_date;
        }
        
        // Show loading in table
        $('#transaction-table-body').html(`
            <tr>
                <td colspan="9" class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </td>
            </tr>
        `);
        
        $.ajax({
            url: `/api/nasabah-badan/${nasabahId}/transactions`,
            method: 'GET',
            data: params,
            headers: {
                'Accept': 'application/json'
            },
            success: function(response) {
                if (response.success) {
                    renderTransactionTable(response.data, response.pagination);
                    renderPagination(response.pagination);
                } else {
                    showNoData();
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                $('#transaction-table-body').html(`
                    <tr>
                        <td colspan="9" class="text-center text-danger">
                            <i class="fas fa-exclamation-triangle"></i> Gagal memuat data. Silakan coba lagi.
                        </td>
                    </tr>
                `);
            }
        });
    }
    
    // Render transaction table
    function renderTransactionTable(transactions, pagination) {
        if (transactions.length === 0) {
            showNoData();
            return;
        }
        
        let html = '';
        const startNumber = pagination.from;
        
        transactions.forEach((transaction, index) => {
            const number = startNumber + index;
            
            // Build detail sampah string
            let detailSampah = '<ul class="mb-0 ps-3">';
            let totalBerat = 0;
            
            transaction.detail_sampah.forEach(detail => {
                totalBerat += parseFloat(detail.berat_kg);
                detailSampah += `<li><small>${detail.sampah_nama} (${detail.sampah_jenis}): ${detail.berat_kg_formatted}</small></li>`;
            });
            
            detailSampah += '</ul>';
            
            // Status badge
            let statusBadge = '';
            if (transaction.status === 'selesai') {
                statusBadge = '<span class="badge badge-success">Selesai</span>';
            } else if (transaction.status === 'pending') {
                statusBadge = '<span class="badge badge-warning">Pending</span>';
            } else {
                statusBadge = '<span class="badge badge-secondary">' + transaction.status + '</span>';
            }
            
            html += `
                <tr>
                    <td>${number}</td>
                    <td><strong>${transaction.kode_transaksi}</strong></td>
                    <td>${transaction.tanggal_transaksi_formatted}</td>
                    <td>${detailSampah}</td>
                    <td><strong>${totalBerat.toFixed(2)} kg</strong></td>
                    <td><strong>${transaction.total_transaksi_formatted}</strong></td>
                    <td>${transaction.petugas.nama}</td>
                    <td>${statusBadge}</td>
                    <td>
                        <button class="btn btn-sm btn-info btn-view-detail" data-id="${transaction.id}" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        
        $('#transaction-table-body').html(html);
        
        // Update showing info
        $('#showing-info').text(
            `Menampilkan ${pagination.from} sampai ${pagination.to} dari ${pagination.total} data`
        );
    }
    
    // Show no data message
    function showNoData() {
        $('#transaction-table-body').html(`
            <tr>
                <td colspan="9" class="text-center text-muted">
                    <i class="fas fa-inbox"></i> Tidak ada data transaksi
                </td>
            </tr>
        `);
        $('#showing-info').text('');
        $('#pagination-container').html('');
    }
    
    // Render pagination
    function renderPagination(pagination) {
        if (pagination.last_page <= 1) {
            $('#pagination-container').html('');
            return;
        }
        
        let html = '';
        
        // Previous button
        if (pagination.current_page > 1) {
            html += `
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${pagination.current_page - 1}">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
            `;
        } else {
            html += `
                <li class="page-item disabled">
                    <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                </li>
            `;
        }
        
        // Page numbers
        const startPage = Math.max(1, pagination.current_page - 2);
        const endPage = Math.min(pagination.last_page, pagination.current_page + 2);
        
        if (startPage > 1) {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`;
            if (startPage > 2) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
        }
        
        for (let i = startPage; i <= endPage; i++) {
            if (i === pagination.current_page) {
                html += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
            } else {
                html += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            }
        }
        
        if (endPage < pagination.last_page) {
            if (endPage < pagination.last_page - 1) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.last_page}">${pagination.last_page}</a></li>`;
        }
        
        // Next button
        if (pagination.current_page < pagination.last_page) {
            html += `
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${pagination.current_page + 1}">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            `;
        } else {
            html += `
                <li class="page-item disabled">
                    <span class="page-link"><i class="fas fa-chevron-right"></i></span>
                </li>
            `;
        }
        
        $('#pagination-container').html(html);
    }
    
    // Pagination click handler
    $(document).on('click', '#pagination-container .page-link', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page) {
            currentPage = page;
            fetchTransactionHistory(page);
        }
    });
    
    // Filter button handler
    $('#btn-filter').on('click', function() {
        currentFilters.start_date = $('#start-date').val();
        currentFilters.end_date = $('#end-date').val();
        
        // Validate dates
        if (currentFilters.start_date && currentFilters.end_date) {
            if (currentFilters.start_date > currentFilters.end_date) {
                alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir!');
                return;
            }
        }
        
        currentPage = 1;
        fetchTransactionHistory(1);
    });
    
    // Reset button handler
    $('#btn-reset').on('click', function() {
        $('#start-date').val('');
        $('#end-date').val('');
        currentFilters.start_date = '';
        currentFilters.end_date = '';
        currentPage = 1;
        fetchTransactionHistory(1);
    });
    
    // View detail button handler (you can implement modal or redirect)
    $(document).on('click', '.btn-view-detail', function() {
        const transactionId = $(this).data('id');
        // Implement detail view logic here
        alert('Detail transaksi ID: ' + transactionId);
        // You can open a modal or redirect to detail page
    });
    
    // Format currency helper
    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    }
    
    // Format date helper
    function formatDate(date) {
        const options = { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        return date.toLocaleDateString('id-ID', options);
    }
    
    // Initial fetch
    fetchNasabahDetail();
    fetchTransactionHistory(1);
});
</script>
@endpush

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
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const nasabahId = {{ $nasabahBadan->id }};
    
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
});
</script>
@endpush

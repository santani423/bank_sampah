@extends('layouts.template')

@section('title', 'Pengiriman Sampah')

@section('main')

@push('style')
{{-- Menggunakan Google Fonts untuk tampilan lebih modern --}}
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('edmate/assets/css/kirim-sampah-responsive.css') }}">
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f4f7fa;
    }

    /* Card Styling */
    .card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        margin-bottom: 1.5rem;
    }

    .card-header {
        border-bottom: 1px solid #edf2f7;
        padding: 1.25rem 1.5rem;
    }

    /* Form Styling */
    .form-label {
        font-weight: 600;
        color: #4a5568;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 0.6rem 1rem;
        transition: all 0.2s;
    }

    .form-control:focus {
        border-color: #4299e1;
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.15);
    }

    /* Informasi Lapak Pill */
    .info-lapak-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem;
    }

    /* Transaction Card */
    .transaksi-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .transaksi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
    }

    /* Table Detail Styling */
    .table-detail-custom {
        background: #f8fafc;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #eef2f7;
    }

    .table-detail-custom table {
        margin-bottom: 0;
    }

    .table-detail-custom thead th {
        background: #f1f5f9;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
        color: #64748b;
        border: none;
    }

    .table-detail-custom tbody td {
        border-color: #f1f5f9;
        vertical-align: middle;
        font-size: 0.9rem;
    }

    /* Badge Custom */
    .badge-status {
        padding: 6px 14px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.025em;
    }

    /* Utility */
    .text-primary-dark { color: #2c5282; }
    .btn-primary { background-color: #3182ce; border: none; border-radius: 10px; padding: 0.6rem 1.5rem; }
    .btn-success { background-color: #38a169; border: none; border-radius: 10px; }

    @media (max-width: 768px) {
        .responsive-card-table tr {
            border: 1px solid #edf2f7;
            margin-bottom: 12px;
            display: block;
            background: white;
            border-radius: 10px;
        }
        .responsive-card-table td {
            display: flex;
            justify-content: space-between;
            padding: 8px 12px !important;
        }
        .responsive-card-table td::before {
            content: attr(data-label);
            font-weight: 700;
            color: #718096;
        }
    }
</style>
@endpush

<div class="container py-4">

    {{-- FORM HEADER --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <div class="d-flex align-items-center">
                <div class="bg-primary text-white p-2 rounded-3 me-3">
                    <i class="bi bi-send-fill fs-4"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold text-primary-dark">Form Pengiriman Sampah</h4>
                    <p class="text-muted small mb-0">Input data pengiriman sampah ke gudang pusat</p>
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            {{-- INFO LAPAK --}}
            <div class="info-lapak-box mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="text-muted small d-block">Nama Lapak</label>
                        <span class="fw-bold">{{ $lapak->nama_lapak }}</span>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small d-block">Kode Lapak</label>
                        <span class="badge bg-soft-primary text-primary px-2">{{ $lapak->kode_lapak }}</span>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small d-block">Cabang</label>
                        <span class="fw-bold text-dark">{{ $lapak->cabang->nama_cabang ?? '-' }}</span>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small d-block">Lokasi Kota</label>
                        <span class="fw-bold text-dark">{{ $lapak->kota ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('petugas.lapak.proses-kirim-sampah', $lapak->id) }}" method="POST">
                @csrf
                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label">Kode Pengiriman</label>
                        <input type="text" class="form-control bg-light fw-bold" name="kode_pengiriman" value="{{ $kodePengiriman }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Kirim</label>
                        <input type="date" class="form-control" name="tanggal_pengiriman" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Gudang Tujuan</label>
                        <select name="customer" class="form-select" required>
                            <option value="">-- Pilih Customer --</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->nama_gudang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Catatan Tambahan (Opsional)</label>
                        <textarea name="catatan" class="form-control" rows="2" placeholder="Tulis instruksi atau catatan pengiriman di sini..."></textarea>
                    </div>
                    <div class="col-12 text-end">
                        <hr class="my-4">
                        <button type="submit" class="btn btn-success shadow-sm px-5 py-2 fw-semibold">
                            <i class="bi bi-check2-circle me-2"></i>Kirim Sampah Sekarang
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- LIST TRANSAKSI --}}
    <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
        <h4 class="fw-bold text-primary-dark mb-0">Riwayat Transaksi Lapak</h4>
        <div class="d-flex gap-2">
            <input type="text" id="searchTransaksi" class="form-control form-control-sm" placeholder="Cari Kode..." style="width: 150px;">
            <input type="date" id="searchTanggal" class="form-control form-control-sm" style="width: 150px;">
            <button id="searchBtn" class="btn btn-primary btn-sm px-3">Cari</button>
        </div>
    </div>

    <div id="transaksiLapakList" class="row g-4">
        {{-- Data dimuat via JS --}}
    </div>

    {{-- PAGINATION --}}
    <div class="card mt-4 border-0 shadow-none bg-transparent">
        <div class="card-body p-0 d-flex justify-content-between align-items-center">
            <p id="paginationInfo" class="text-muted small mb-0"></p>
            <nav>
                <ul class="pagination mb-0">
                    <li class="page-item"><button id="prevPage" class="btn btn-white btn-sm border me-2 px-3">Previous</button></li>
                    <li class="page-item"><button id="nextPage" class="btn btn-white btn-sm border px-3">Next</button></li>
                </ul>
            </nav>
        </div>
    </div>

    {{-- REKAP TABLE --}}
    <div id="rekapSampahTable" class="mt-5"></div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const lapakId = @json($lapak->id);
    const list = document.getElementById('transaksiLapakList');
    const searchInput = document.getElementById('searchTransaksi');
    const searchTanggal = document.getElementById('searchTanggal');
    const searchBtn = document.getElementById('searchBtn');
    const prevBtn = document.getElementById('prevPage');
    const nextBtn = document.getElementById('nextPage');
    const info = document.getElementById('paginationInfo');

    let page = 1;
    let lastPage = 1;
    let limit = 4; 

    function loadData() {
        list.innerHTML = `<div class="col-12 text-center py-5"><div class="spinner-border text-primary"></div></div>`;
        
        let url = `/api/lapak/${lapakId}/transaksi?page=${page}&limit=${limit}`;
        if (searchInput.value) url += `&search=${searchInput.value}`;
        if (searchTanggal.value) url += `&tanggal=${searchTanggal.value}`;

        fetch(url)
            .then(res => res.json())
            .then(res => {
                const data = res.data || res;
                lastPage = res.last_page || 1;
                info.textContent = `Menampilkan halaman ${page} dari ${lastPage}`;
                list.innerHTML = '';

                // Render Rekap
                updateRekap(data);

                if (!data.length) {
                    list.innerHTML = `<div class="col-12 text-center py-5 text-muted">Belum ada riwayat transaksi.</div>`;
                    return;
                }

                data.forEach(trx => {
                    let statusColor = trx.status === 'selesai' ? 'bg-success text-white' : (trx.status === 'pending' ? 'bg-warning text-dark' : 'bg-danger text-white');
                    
                    let detailRows = '';
                    trx.detail_transaksi.forEach((item, idx) => {
                        detailRows += `
                            <tr>
                                <td data-label="#">${idx + 1}</td>
                                <td data-label="Jenis Sampah"><strong>${item.sampah ? item.sampah.nama_sampah : '-'}</strong></td>
                                <td data-label="Berat">${parseFloat(item.berat_kg).toLocaleString('id-ID')} kg</td>
                                <td data-label="Harga/Kg">Rp ${parseFloat(item.harga_per_kg).toLocaleString('id-ID')}</td>
                                <td data-label="Subtotal" class="text-md-end fw-bold text-primary">Rp ${parseFloat(item.total_harga).toLocaleString('id-ID')}</td>
                            </tr>`;
                    });

                    list.innerHTML += `
                    <div class="col-12">
                        <div class="card transaksi-card">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted x-small d-block">ID Transaksi</span>
                                    <h6 class="mb-0 fw-bold">${trx.kode_transaksi}</h6>
                                </div>
                                <div class="text-end">
                                    <span class="badge-status ${statusColor}">${trx.status.toUpperCase()}</span>
                                    <span class="d-block small text-muted mt-1">${trx.tanggal_transaksi}</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-detail-custom p-1">
                                    <table class="table responsive-card-table">
                                        <thead>
                                            <tr>
                                                <th>#</th><th>Jenis Sampah</th><th>Berat</th><th>Harga/Kg</th><th class="text-end">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>${detailRows}</tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0 d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <span class="text-muted small">Total Pembayaran</span>
                                    <h5 class="mb-0 fw-bold text-success">Rp ${Number(trx.total_transaksi).toLocaleString('id-ID')}</h5>
                                </div>
                                <a href="/petugas/lapak/transaksi/${trx.id}" class="btn btn-outline-primary btn-sm rounded-pill px-4 fw-bold">
                                    Lihat Bukti <i class="bi bi-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>`;
                });
            });
    }

    function updateRekap(data) {
        let rekap = {};
        data.forEach(trx => {
            if (trx.detail_transaksi) {
                trx.detail_transaksi.forEach(item => {
                    let nama = item.sampah ? item.sampah.nama_sampah : '-';
                    rekap[nama] = (rekap[nama] || 0) + (parseFloat(item.berat_kg) || 0);
                });
            }
        });

        let rekapHtml = '';
        if (Object.keys(rekap).length > 0) {
            rekapHtml = `
                <div class="card border-0 bg-dark shadow-sm overflow-hidden">
                    <div class="card-header bg-dark border-0 py-3">
                        <h6 class="text-white mb-0 fw-bold"><i class="bi bi-pie-chart-fill me-2"></i>Rekap Berat Per Jenis (Halaman Ini)</h6>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-dark table-hover mb-0 small">
                            <thead><tr class="text-muted"><th>Jenis Sampah</th><th class="text-end">Total Berat</th></tr></thead>
                            <tbody>
                                ${Object.entries(rekap).map(([n, b]) => `<tr><td class="ps-3">${n}</td><td class="text-end pe-3 fw-bold text-warning">${b.toLocaleString('id-ID')} kg</td></tr>`).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>`;
        }
        document.getElementById('rekapSampahTable').innerHTML = rekapHtml;
    }

    searchBtn.onclick = () => { page = 1; loadData(); }
    prevBtn.onclick = () => { if (page > 1) { page--; loadData(); } }
    nextBtn.onclick = () => { if (page < lastPage) { page++; loadData(); } }

    loadData();
});
</script>
@endpush

@endsection
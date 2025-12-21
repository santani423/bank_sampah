@extends('layouts.template')

@section('title', 'Pengiriman Sampah')

@section('main')

@push('style')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('edmate/assets/css/kirim-sampah-responsive.css') }}">
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f4f7fa;
    }

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

    /* Styling Table dengan Border Tegas */
    .table-bordered-custom {
        border: 1px solid #e2e8f0;
    }

    .table-bordered-custom th {
        background-color: #f8fafc;
        border-bottom: 2px solid #e2e8f0 !important;
        color: #4a5568;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
    }

    .table-bordered-custom td, .table-bordered-custom th {
        border: 1px solid #e2e8f0 !important;
        vertical-align: middle;
    }

    .info-lapak-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem;
    }

    .preview-container {
        width: 100%;
        height: 180px;
        border: 2px dashed #cbd5e0;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background-color: #f8fafc;
        position: relative;
    }

    .preview-mini {
        width: 80px;
        height: 60px;
        border: 1px dashed #cbd5e0;
        border-radius: 8px;
        overflow: hidden;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .preview-container img, .preview-mini img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .preview-placeholder {
        color: #a0aec0;
        text-align: center;
        font-size: 0.8rem;
    }

    .transaksi-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .badge-status {
        padding: 6px 14px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .text-primary-dark { color: #2c5282; }
    .btn-primary { background-color: #3182ce; border: none; border-radius: 10px; padding: 0.6rem 1.5rem; }
    .btn-success { background-color: #38a169; border: none; border-radius: 10px; }
    
    /* Tombol Simpan Akhir */
    .btn-save-final {
        background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
        color: white;
        border: none;
        padding: 12px 40px;
        border-radius: 12px;
        font-weight: 700;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s;
    }

    .btn-save-final:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        color: #fff;
    }

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
            border: none !important;
            border-bottom: 1px solid #f1f5f9 !important;
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

    {{-- FORM UTAMA --}}
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
            <div class="info-lapak-box mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="text-muted small d-block">Nama Lapak</label>
                        <span class="fw-bold">{{ $lapak->nama_lapak }}</span>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small d-block">Kode Lapak</label>
                        <span class="badge bg-primary bg-opacity-10 text-primary px-2">{{ $lapak->kode_lapak }}</span>
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

            <form action="{{ route('petugas.lapak.proses-kirim-sampah', $lapak->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label">Kode Pengiriman</label>
                        <input type="text" class="form-control bg-light fw-bold" name="kode_pengiriman" value="{{ $kodePengiriman }}" readonly required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Kirim</label>
                        <input type="date" class="form-control" name="tanggal_pengiriman" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Gudang Tujuan</label>
                        <select name="customer" class="form-select" required>
                            <option value="" disabled selected>-- Pilih Customer --</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->nama_gudang }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Nama Driver / Supir</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-person-badge"></i></span>
                            <input type="text" name="nama_driver" class="form-control" placeholder="Nama pengemudi" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">No. Telepon Driver</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-whatsapp"></i></span>
                            <input type="tel" name="telp_driver" class="form-control" placeholder="08xxxx" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">No. Plat Kendaraan</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-truck"></i></span>
                            <input type="text" name="plat_nomor" class="form-control" placeholder="B 1234 ABC" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Foto Sampah (Muatan)</label>
                        <div class="preview-container mb-2" id="preview-container-sampah">
                            <div class="preview-placeholder"><i class="bi bi-camera fs-1 d-block"></i>Pratinjau Foto</div>
                        </div>
                        <input type="file" name="foto_sampah" class="form-control" accept="image/*" onchange="previewImage(this, 'preview-container-sampah')" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Foto Plat Nomor Mobil</label>
                        <div class="preview-container mb-2" id="preview-container-plat">
                            <div class="preview-placeholder"><i class="bi bi-truck-flatbed fs-1 d-block"></i>Pratinjau Plat</div>
                        </div>
                        <input type="file" name="foto_plat" class="form-control" accept="image/*" onchange="previewImage(this, 'preview-container-plat')" required>
                    </div>

                    <div class="col-12 text-end">
                        <hr class="my-4">
                        <button type="submit" class="btn btn-success shadow-sm px-5 py-2 fw-semibold">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Ke Riwayat Sementara
                        </button>
                            <button type="button" id="ajaxSubmit" class="btn btn-success shadow-sm px-5 py-2 fw-semibold">
                                <i class="bi bi-plus-circle me-2"></i>Tambah Ke Riwayat Sementara
                            </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- LIST TRANSAKSI --}}
    <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
        <h4 class="fw-bold text-primary-dark mb-0">Daftar Pengiriman (Halaman Ini)</h4>
        <div class="d-flex gap-2">
            <input type="text" id="searchTransaksi" class="form-control form-control-sm" placeholder="Cari Kode..." style="width: 150px;">
            <button id="searchBtn" class="btn btn-primary btn-sm px-3">Cari</button>
        </div>
    </div>

    <div id="transaksiLapakList" class="row g-4"></div>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
        <p id="paginationInfo" class="text-muted small mb-0"></p>
        <div class="btn-group">
            <button id="prevPage" class="btn btn-outline-secondary btn-sm px-3">Prev</button>
            <button id="nextPage" class="btn btn-outline-secondary btn-sm px-3">Next</button>
        </div>
    </div>

    {{-- REKAP TABEL --}}
    <div id="rekapSampahTable" class="mt-5 mb-5"></div>

    {{-- TOMBOL SIMPAN AKHIR --}}
    <div class="card border-0 bg-transparent mb-5">
        <div class="card-body p-0 text-center">
            <div class="alert alert-info border-0 shadow-sm rounded-4 mb-4">
                <i class="bi bi-info-circle-fill me-2"></i> Pastikan semua data pengiriman di atas sudah benar sebelum menyimpan secara permanen.
            </div>
            <a href="{{ route('petugas.lapak.index') }}" class="btn btn-save-final">
                <i class="bi bi-cloud-check-fill me-2"></i> SIMPAN SEMUA DATA & SELESAI
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewImage(input, containerId) {
    const container = document.getElementById(containerId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            container.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const lapakId = @json($lapak->id);
    const list = document.getElementById('transaksiLapakList');
    const searchInput = document.getElementById('searchTransaksi');
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

        fetch(url)
            .then(res => res.json())
            .then(res => {
                const data = res.data || res;
                lastPage = res.last_page || 1;
                info.textContent = `Halaman ${page} dari ${lastPage}`;
                list.innerHTML = '';
                updateRekap(data);

                if (!data.length) {
                    list.innerHTML = `<div class="col-12 text-center py-5 text-muted">Belum ada riwayat.</div>`;
                    return;
                }

                data.forEach(trx => {
                    let statusColor = trx.status === 'selesai' ? 'bg-success text-white' : 'bg-warning text-dark';
                    let detailRows = '';
                    trx.detail_transaksi.forEach((item, idx) => {
                        detailRows += `
                            <tr>
                                <td data-label="#">${idx + 1}</td>
                                <td data-label="Jenis">${item.sampah ? item.sampah.nama_sampah : '-'}</td>
                                <td data-label="Berat">${parseFloat(item.berat_kg)} kg</td>
                                <td data-label="Harga">Rp ${parseFloat(item.harga_per_kg).toLocaleString()}</td>
                                <td data-label="Total" class="text-md-end fw-bold">Rp ${parseFloat(item.total_harga).toLocaleString()}</td>
                            </tr>`;
                    });

                    list.innerHTML += `
                    <div class="col-12">
                        <div class="card transaksi-card border shadow-sm">
                            <div class="card-header bg-white d-flex justify-content-between">
                                <span class="fw-bold text-primary">${trx.kode_transaksi}</span>
                                <span class="badge ${statusColor}">${trx.status.toUpperCase()}</span>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3 bg-light p-2 rounded mx-0 small">
                                    <div class="col-md-4 border-end"><b>Driver:</b> ${trx.nama_driver || '-'}</div>
                                    <div class="col-md-4 border-end"><b>Telp:</b> ${trx.telp_driver || '-'}</div>
                                    <div class="col-md-4"><b>Plat:</b> ${trx.plat_nomor || '-'}</div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered-custom responsive-card-table mb-0">
                                        <thead><tr><th>#</th><th>Jenis</th><th>Berat</th><th>Harga</th><th class="text-end">Total</th></tr></thead>
                                        <tbody>${detailRows}</tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top py-3">
                                <div class="row align-items-center">
                                    <div class="col-md-7">
                                         <form action="/petugas/lapak/update-foto-transaksi/${trx.id}" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="preview-mini" id="preview-update-${trx.id}"><i class="bi bi-image"></i></div>
                                                <div class="input-group input-group-sm">
                                                    <input type="file" name="foto_sampah_update" class="form-control" onchange="previewImage(this, 'preview-update-${trx.id}')" required>
                                                    <button type="submit" class="btn btn-dark"><i class="bi bi-upload"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-5 text-md-end mt-2 mt-md-0">
                                        <h6 class="mb-0 fw-bold text-success">Total: Rp ${Number(trx.total_transaksi).toLocaleString('id-ID')}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                });
            });
    }

    function updateRekap(data) {
        let rekap = {};
        data.forEach(trx => {
            trx.detail_transaksi?.forEach(item => {
                let nama = item.sampah ? item.sampah.nama_sampah : '-';
                rekap[nama] = (rekap[nama] || 0) + (parseFloat(item.berat_kg) || 0);
            });
        });
        let rekapHtml = '';
        if (Object.keys(rekap).length > 0) {
            rekapHtml = `
                <div class="card border-0 bg-dark shadow overflow-hidden">
                    <div class="card-header bg-dark border-0"><h6 class="text-white mb-0">Rekapitulasi Berat Halaman Ini</h6></div>
                    <div class="card-body p-0">
                        <table class="table table-dark table-bordered border-secondary mb-0 small">
                            <thead><tr><th class="border-secondary">Jenis Sampah</th><th class="text-end border-secondary">Total Berat</th></tr></thead>
                            <tbody>
                                ${Object.entries(rekap).map(([n, b]) => `<tr><td class="border-secondary">${n}</td><td class="text-end text-warning border-secondary">${b.toLocaleString()} kg</td></tr>`).join('')}
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
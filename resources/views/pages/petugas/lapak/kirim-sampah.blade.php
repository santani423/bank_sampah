@extends('layouts.template')

@section('title', 'Pengiriman Sampah')

@section('main')

@push('style')
<link rel="stylesheet" href="{{ asset('edmate/assets/css/kirim-sampah-responsive.css') }}">
@endpush

<div class="container mt-4">

    {{-- ================= FORM & DETAIL TRANSAKSI ================= --}}
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Form Pengiriman Sampah</h4>
                </div>

                <div class="card-body">

                    {{-- INFORMASI LAPAK --}}
                    <div class="mb-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-info text-white">
                                <strong>Informasi Lapak</strong>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <small class="text-muted">Nama Lapak</small>
                                        <div class="fw-semibold">{{ $lapak->nama_lapak }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">Kode Lapak</small>
                                        <div class="fw-semibold">{{ $lapak->kode_lapak }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">Cabang</small>
                                        <div class="fw-semibold">{{ $lapak->cabang->nama_cabang ?? '-' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">Kota</small>
                                        <div class="fw-semibold">{{ $lapak->kota ?? '-' }}</div>
                                    </div>
                                    <div class="col-12">
                                        <small class="text-muted">Alamat</small>
                                        <div class="fw-semibold">{{ $lapak->alamat }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- FORM KIRIM --}}
                    <form action="{{ route('petugas.lapak.proses-kirim-sampah', $lapak->id) }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label>Kode Pengiriman</label>
                                <input type="text" class="form-control" name="kode_pengiriman"
                                    value="{{ $kodePengiriman }}" readonly required>
                            </div>

                            <div class="col-md-6">
                                <label>Tanggal Pengiriman</label>
                                <input type="date" class="form-control" name="tanggal_pengiriman"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label>Customer</label>
                                <select name="customer" class="form-control" required>
                                    <option value="">Pilih Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">
                                            {{ $customer->nama_gudang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label>Catatan</label>
                                <textarea name="catatan" class="form-control" rows="2"></textarea>
                            </div>

                            <div class="col-12">
                                <button class="btn btn-success">Kirim Sampah</button>
                                <a href="{{ route('petugas.lapak.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- ================= LIST TRANSAKSI (CARD VIEW) ================= --}}
    <div class="row mt-4">
        <div class="col-12">

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text-break text-center text-md-start" style="white-space:normal;word-break:break-word;">Data Transaksi Lapak</h4>
                    <div class="d-flex gap-2">
                        <input type="text" id="searchTransaksi" class="form-control"
                            placeholder="Cari kode..." style="max-width:180px;">
                        <input type="date" id="searchTanggal" class="form-control" style="max-width:160px;">
                        <button id="searchBtn" class="btn btn-primary">Cari</button>
                    </div>
                </div>

                <div class="card-body">

                    <!-- REKAP JUMLAH SAMPAH PER JENIS dipindah ke bawah -->
                    {{-- CARD LIST --}}
                    <div id="transaksiLapakList" class="row g-3">
                        <div class="col-12 text-center text-muted">Memuat data...</div>
                    </div>
                    <style>
                        .transaksi-card {
                            transition: box-shadow 0.2s, transform 0.2s;
                            border-radius: 16px;
                            border: 1px solid #e5e7eb;
                            min-width: 0;
                            display: flex;
                            flex-direction: column;
                            height: 100%;
                        }
                        .transaksi-card:hover {
                            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
                            transform: translateY(-4px) scale(1.01);
                            border-color: #458EFF;
                        }
                        .transaksi-badge {
                            font-size: 0.95em;
                            padding: 0.4em 1em;
                            border-radius: 12px;
                        }
                        .transaksi-icon {
                            font-size: 1.5em;
                            margin-right: 0.5em;
                            vertical-align: middle;
                        }
                        @media (max-width: 991.98px) {
                            #transaksiLapakList .col-md-6, #transaksiLapakList .col-lg-4 {
                                flex: 0 0 100%;
                                max-width: 100%;
                            }
                        }
                        @media (max-width: 575.98px) {
                            .transaksi-card {
                                border-radius: 10px;
                                padding: 0.5rem;
                            }
                            .transaksi-card .card-header, .transaksi-card .card-body, .transaksi-card .card-footer {
                                padding-left: 0.5rem !important;
                                padding-right: 0.5rem !important;
                            }
                            .transaksi-card .table {
                                font-size: 0.85em;
                            }
                        }
                        .transaksi-card .table-responsive {
                            overflow-x: auto;
                        }
                        .transaksi-card .table {
                            min-width: 420px;
                        }
                    </style>

                    {{-- PAGINATION --}}
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div id="paginationInfo" class="text-muted"></div>
                        <div>
                            <button id="prevPage" class="btn btn-outline-secondary btn-sm">&laquo; Prev</button>
                            <button id="nextPage" class="btn btn-outline-secondary btn-sm">Next &raquo;</button>
                        </div>
                    </div>
                    {{-- REKAP JUMLAH SAMPAH PER JENIS (sekarang di bawah) --}}
                    <div id="rekapSampahTable" class="mt-4"></div>

                </div>
            </div>

        </div>
    </div>

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
    let limit = 10;
    let search = '';
    let tanggal = '';

    function loadData() {

        list.innerHTML = `<div class="col-12 text-center text-muted">Memuat data...</div>`;
        document.getElementById('rekapSampahTable').innerHTML = '';

        let url = `/api/lapak/${lapakId}/transaksi?page=${page}&limit=${limit}`;
        if (search) url += `&search=${search}`;
        if (tanggal) url += `&tanggal=${tanggal}`;

        fetch(url)
            .then(res => res.json())
            .then(res => {

                const data = res.data || res;
                lastPage = res.last_page || 1;

                info.textContent = `Halaman ${page} dari ${lastPage}`;

                list.innerHTML = '';

                // Rekap jumlah sampah per jenis
                let rekap = {};
                data.forEach(trx => {
                    if (trx.detail_transaksi && trx.detail_transaksi.length > 0) {
                        trx.detail_transaksi.forEach(item => {
                            let nama = item.sampah ? item.sampah.nama_sampah : '-';
                            let berat = parseFloat(item.berat_kg) || 0;
                            if (!rekap[nama]) rekap[nama] = 0;
                            rekap[nama] += berat;
                        });
                    }
                });
                // Render tabel rekap
                let rekapHtml = '';
                if (Object.keys(rekap).length > 0) {
                    rekapHtml += `<div class="table-responsive"><table class="table table-bordered table-sm mb-0"><thead class="table-light"><tr><th colspan="2" class="text-center">Rekap Jumlah Sampah per Jenis</th></tr><tr><th>Jenis Sampah</th><th>Total Berat (kg)</th></tr></thead><tbody>`;
                    Object.entries(rekap).forEach(([nama, berat]) => {
                        rekapHtml += `<tr><td>${nama}</td><td>${berat.toLocaleString('id-ID')}</td></tr>`;
                    });
                    rekapHtml += `</tbody></table></div>`;
                } else {
                    rekapHtml = '<div class="text-muted text-center">Tidak ada data rekap sampah</div>';
                }
                document.getElementById('rekapSampahTable').innerHTML = rekapHtml;

                if (!data.length) {
                    list.innerHTML = `<div class="col-12 text-center text-muted">Tidak ada data</div>`;
                    return;
                }

                data.forEach(trx => {
                    let badge =
                        trx.status === 'selesai' ? 'bg-success transaksi-badge' :
                        trx.status === 'pending' ? 'bg-warning text-dark transaksi-badge' :
                        'bg-danger transaksi-badge';
                    let icon =
                        trx.status === 'selesai' ? '<i class="bi bi-check-circle-fill text-success transaksi-icon"></i>' :
                        trx.status === 'pending' ? '<i class="bi bi-hourglass-split text-warning transaksi-icon"></i>' :
                        '<i class="bi bi-x-circle-fill text-danger transaksi-icon"></i>';
                    let detailRows = '';
                    if (trx.detail_transaksi && trx.detail_transaksi.length > 0) {
                        trx.detail_transaksi.forEach((item, idx) => {
                            detailRows += `
                                <tr>
                                    <td>${idx + 1}</td>
                                    <td>${item.sampah ? item.sampah.nama_sampah : '-'}</td>
                                    <td>${parseFloat(item.berat_kg).toLocaleString('id-ID')} kg</td>
                                    <td>Rp ${parseFloat(item.harga_per_kg).toLocaleString('id-ID')}</td>
                                    <td>Rp ${parseFloat(item.total_harga).toLocaleString('id-ID')}</td>
                                </tr>
                            `;
                        });
                    } else {
                        detailRows = `<tr><td colspan="5" class="text-center text-muted">Tidak ada detail</td></tr>`;
                    }
                    list.innerHTML += `
                    <div class="col-12 col-md-6 col-lg-4 d-flex">
                        <div class="card transaksi-card shadow-sm flex-fill">
                            <div class="card-header d-flex justify-content-between align-items-center bg-white border-0 pb-2">
                                <div>${icon}<strong>${trx.kode_transaksi}</strong></div>
                                <span class="${badge}">${trx.status.charAt(0).toUpperCase() + trx.status.slice(1)}</span>
                            </div>
                            <div class="card-body pt-2 pb-2">
                                <div class="d-flex flex-column gap-1 mb-2">
                                    <span><i class="bi bi-calendar-event me-1 text-primary"></i> <b>Tanggal:</b> ${trx.tanggal_transaksi}</span>
                                    <span><i class="bi bi-cash-coin me-1 text-success"></i> <b>Total:</b> Rp ${Number(trx.total_transaksi).toLocaleString('id-ID')}</span>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Sampah</th>
                                                <th>Berat</th>
                                                <th>Harga/Kg</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${detailRows}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-0 text-end pt-0">
                                <a href="/petugas/lapak/transaksi/${trx.id}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </div>
                        </div>
                    </div>`;
                });
            });
    }

    searchBtn.onclick = () => { page = 1; search = searchInput.value; tanggal = searchTanggal.value; loadData(); }
    prevBtn.onclick = () => { if (page > 1) { page--; loadData(); } }
    nextBtn.onclick = () => { if (page < lastPage) { page++; loadData(); } }

    loadData();
});
</script>
@endpush

@endsection

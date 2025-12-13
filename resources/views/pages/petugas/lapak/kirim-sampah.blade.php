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
                    <h4 class="mb-0">Data Transaksi Lapak</h4>
                    <div class="d-flex gap-2">
                        <input type="text" id="searchTransaksi" class="form-control"
                            placeholder="Cari kode..." style="max-width:180px;">
                        <input type="date" id="searchTanggal" class="form-control" style="max-width:160px;">
                        <button id="searchBtn" class="btn btn-primary">Cari</button>
                    </div>
                </div>

                <div class="card-body">

                    {{-- CARD LIST --}}
                    <div id="transaksiLapakList" class="row g-3">
                        <div class="col-12 text-center text-muted">Memuat data...</div>
                    </div>

                    {{-- PAGINATION --}}
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div id="paginationInfo" class="text-muted"></div>
                        <div>
                            <button id="prevPage" class="btn btn-outline-secondary btn-sm">&laquo; Prev</button>
                            <button id="nextPage" class="btn btn-outline-secondary btn-sm">Next &raquo;</button>
                        </div>
                    </div>

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

                if (!data.length) {
                    list.innerHTML = `<div class="col-12 text-center text-muted">Tidak ada data</div>`;
                    return;
                }

                data.forEach(trx => {

                    let badge =
                        trx.status === 'selesai' ? 'bg-success' :
                        trx.status === 'pending' ? 'bg-warning text-dark' :
                        'bg-danger';

                    list.innerHTML += `
                    <div class="col-md-12 col-lg-12  ">
                        <div class="card shadow-sm h-100">
                            <div class="card-header d-flex justify-content-between">
                                <strong>${trx.kode_transaksi}</strong>
                                <span class="badge ${badge}">${trx.status}</span>
                            </div>
                            <div class="card-body">
                                <p class="mb-1"><b>Tanggal:</b> ${trx.tanggal_transaksi}</p>
                                <p class="mb-1"><b>Total:</b> Rp ${Number(trx.total_transaksi).toLocaleString('id-ID')}</p>
                            </div>
                            <div class="card-footer text-end">
                                <a href="/petugas/lapak/transaksi/${trx.id}" class="btn btn-sm btn-info">
                                    Detail
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

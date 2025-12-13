@extends('layouts.template')

@section('title', 'Pengiriman Sampah')

@section('main')
    @push('style')
        <link rel="stylesheet" href="{{ asset('edmate/assets/css/kirim-sampah-responsive.css') }}">
    @endpush
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0" style="color: #fff;">Form Pengiriman Sampah</h4>
                    </div>
                    <div class="card-body">
                        <div id="transaksi-detail" class="mb-4"></div>
                        {{-- Tabel hanya detail_transaksi --}}
                        @php
                            $detailList = [];
                            if (isset($data) && count($data) > 0) {
                                foreach ($data as $transaksi) {
                                    foreach ($transaksi['detail_transaksi'] as $detail) {
                                        $detailList[] = [
                                            'kode_transaksi' => $transaksi['kode_transaksi'],
                                            'tanggal_transaksi' => $transaksi['tanggal_transaksi'],
                                            'sampah_id' => $detail['sampah_id'],
                                            'berat_kg' => $detail['berat_kg'],
                                            'harga_per_kg' => $detail['harga_per_kg'],
                                            'total_harga' => $detail['total_harga'],
                                            'status' => $transaksi['status'],
                                        ];
                                    }
                                }
                            }
                        @endphp
                        @if (count($detailList) > 0)
                            <div class="mb-4">
                                <h5 class="mb-3">Detail Transaksi Sampah</h5>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Kode Transaksi</th>
                                            <th>Tanggal</th>
                                            <th>Nama Sampah</th>
                                            <th>Berat (kg)</th>
                                            <th>Harga/kg</th>
                                            <th>Total Harga</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($detailList as $detail)
                                            <tr>
                                                <td>{{ $detail['kode_transaksi'] }}</td>
                                                <td>{{ $detail['tanggal_transaksi'] }}</td>
                                                <td>{{ $detail['sampah_id'] }}</td>
                                                <td>{{ $detail['berat_kg'] }}</td>
                                                <td>{{ $detail['harga_per_kg'] }}</td>
                                                <td>{{ $detail['total_harga'] }}</td>
                                                <td>{{ $detail['status'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">Belum ada detail transaksi sampah untuk lapak ini.</div>
                        @endif
                        @push('scripts')
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    var lapakId = {{ $lapak->id }};
                                    var detailDiv = document.getElementById('transaksi-detail');
                                    detailDiv.innerHTML = '<div class="text-center text-muted py-3">Memuat detail transaksi...</div>';
                                    fetch(`/api/lapak/${lapakId}/transaksi`)
                                        .then(response => response.json())
                                        .then(data => {
                                            if (!data.transaksi || data.transaksi.length === 0) {
                                                detailDiv.innerHTML =
                                                    '<div class="alert alert-info">Belum ada transaksi sampah untuk lapak ini.</div>';
                                                return;
                                            }
                                            let html = '<h5 class="mb-3">Detail Transaksi Sampah</h5>';
                                            data.transaksi.forEach(function(trx, idx) {
                                                html +=
                                                    `<div class='card mb-2'>
                                            <div class='card-header bg-light'>
                                                <b>Kode Transaksi:</b> ${trx.kode_transaksi ?? '-'} | <b>Tanggal:</b> ${trx.tanggal_transaksi ?? '-'}
                                            </div>
                                            <div class='card-body'>
                                                <table class='table table-sm table-bordered mb-0'>
                                                    <thead><tr><th>Nama Sampah</th><th>Berat (kg)</th><th>Harga/kg</th><th>Total</th></tr></thead><tbody>`;
                                                trx.detail_transaksi.forEach(function(item) {
                                                    html += `<tr>
                                                <td>${item.sampah_id ?? '-'}</td>
                                                <td>${item.berat_kg ?? '-'}</td>
                                                <td>${item.harga_per_kg ?? '-'}</td>
                                                <td>${item.total_harga ?? '-'}</td>
                                            </tr>`;
                                                });
                                                html += `</tbody></table></div></div>`;
                                            });
                                            detailDiv.innerHTML = html;
                                        })
                                        .catch(() => {
                                            detailDiv.innerHTML = '<div class="alert alert-danger">Gagal memuat data transaksi.</div>';
                                        });
                                });
                            </script>
                        @endpush
                        <div class="mb-4">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-info text-white d-flex align-items-center">
                                    <i class="bi bi-shop-window me-2" style="font-size: 1.5rem;"></i>
                                    <span class="fw-bold">Informasi Lapak</span>
                                </div>
                                <div class="card-body py-3">
                                    <div class="row g-2">
                                        <div class="col-6 mb-2">
                                            <span class="text-muted"><i class="bi bi-person-badge me-1"></i> Nama
                                                Lapak</span><br>
                                            <span class="fw-semibold">{{ $lapak->nama_lapak }}</span>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <span class="text-muted"><i class="bi bi-upc-scan me-1"></i> Kode
                                                Lapak</span><br>
                                            <span class="fw-semibold">{{ $lapak->kode_lapak }}</span>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <span class="text-muted"><i class="bi bi-building me-1"></i> Cabang</span><br>
                                            <span class="fw-semibold">{{ $lapak->cabang->nama_cabang ?? '-' }}</span>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <span class="text-muted"><i class="bi bi-geo-alt me-1"></i> Kota</span><br>
                                            <span class="fw-semibold">{{ $lapak->kota ?? '-' }}</span>
                                        </div>
                                        <div class="col-12">
                                            <span class="text-muted"><i class="bi bi-house-door me-1"></i> Alamat</span><br>
                                            <span class="fw-semibold">{{ $lapak->alamat }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('petugas.lapak.proses-kirim-sampah', $lapak->id ?? '') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="kode_pengiriman" class="form-label">Kode Pengiriman</label>
                                    <input type="text" name="kode_pengiriman" id="kode_pengiriman" class="form-control" value="{{ $kodePengiriman ?? '' }}" readonly required>
                                </div>
                                <div class="col-md-6">
                                    <label for="tanggal_pengiriman" class="form-label">Tanggal Pengiriman</label>
                                    <input type="date" name="tanggal_pengiriman" id="tanggal_pengiriman" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="customer" class="form-label">Customer</label>
                                    <select name="customer" id="customer" class="form-control" required>
                                        <option value="" disabled selected>Pilih Customer</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">({{ $customer->nama_gudang }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="catatan" class="form-label">Catatan</label>
                                    <textarea name="catatan" id="catatan" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="col-12 d-flex gap-2 mt-2">
                                    <button type="submit" class="btn btn-success">Kirim Sampah</button>
                                    <a href="{{ route('petugas.lapak.index') }}" class="btn btn-secondary">Batal</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- List Data Transaksi Lapak (paling bawah) -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card detail-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Data Transaksi Lapak</h4>
                        <div class="d-flex">
                            <input type="text" id="searchTransaksi" class="form-control me-2"
                                placeholder="Cari kode transaksi..." style="max-width:180px;">
                            <input type="date" id="searchTanggal" class="form-control me-2" style="max-width:160px;">
                            <button id="searchBtn" class="btn btn-primary">Cari</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="transaksiLapakTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Kode Transaksi</th>
                                        <th>Tanggal</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Memuat data...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div id="paginationInfo" class="text-muted"></div>
                            <div>
                                <button id="prevPage" class="btn btn-outline-secondary btn-sm me-2">&laquo; Prev</button>
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
            document.addEventListener('DOMContentLoaded', function() {
                var lapakId = @json($lapak->id);
                var tableBody = document.querySelector('#transaksiLapakTable tbody');
                var searchInput = document.getElementById('searchTransaksi');
                var searchBtn = document.getElementById('searchBtn');
                var searchTanggal = document.getElementById('searchTanggal');
                var prevPageBtn = document.getElementById('prevPage');
                var nextPageBtn = document.getElementById('nextPage');
                var paginationInfo = document.getElementById('paginationInfo');
                var currentPage = 1;
                var limit = 10;
                var lastPage = 1;
                var currentSearch = '';
                var currentTanggal = '';

                function loadTransaksi(page = 1, search = '', tanggal = '') {
                    tableBody.innerHTML = `<tr><td colspan="6" class="text-center text-muted">Memuat data...</td></tr>`;
                    let url = `/api/lapak/${lapakId}/transaksi?page=${page}&limit=${limit}`;
                    if (search) url += `&search=${encodeURIComponent(search)}`;
                    if (tanggal) url += `&tanggal=${encodeURIComponent(tanggal)}`;
                    fetch(url)
                        .then(response => response.json())
                        .then(res => {
                            var data = res.data || res;
                            var total = res.total || data.length;
                            lastPage = res.last_page || Math.ceil(total / limit);
                            currentPage = res.current_page || page;
                            paginationInfo.textContent =
                                `Halaman ${currentPage} dari ${lastPage} | Total: ${total}`;
                            tableBody.innerHTML = '';
                            if (!Array.isArray(data) || data.length === 0) {
                                tableBody.innerHTML =
                                    `<tr><td colspan="6" class="text-center text-muted">Belum ada data transaksi lapak</td></tr>`;
                                return;
                            }
                            data.forEach(function(trx, idx) {
                                let statusLabel = '';
                                if (trx.approval === 'selesai' || trx.status === 'selesai') {
                                    statusLabel =
                                        '<span class="badge badge-success" style="color:#000 !important;">Selesai</span>';
                                } else if (trx.approval === 'pending' || trx.status === 'pending') {
                                    statusLabel =
                                        '<span class="badge badge-warning" style="color:#000 !important;">Pending</span>';
                                } else {
                                    statusLabel =
                                        '<span class="badge badge-danger" style="color:#000 !important;">Dibatalkan</span>';
                                }
                                let jumlah = trx.total_transaksi ? Number(trx.total_transaksi)
                                    .toLocaleString('id-ID') : '-';
                                let tanggal = trx.tanggal_transaksi ? new Date(trx.tanggal_transaksi)
                                    .toLocaleString('id-ID') : '-';
                                let detailUrl = `/petugas/lapak/transaksi/${trx.id}`;
                                tableBody.innerHTML += `
                                <tr>
                                    <td>${(idx + 1) + ((currentPage - 1) * limit)}</td>
                                    <td>${trx.kode_transaksi || '-'}</td>
                                    <td>${tanggal}</td>
                                    <td>${jumlah}</td>
                                    <td>${statusLabel}</td>
                                    <td>
                                        <a href="${detailUrl}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i> Detail Transaksi
                                        </a>
                                    </td>
                                </tr>
                            `;
                            });
                        })
                        .catch((err) => {
                            tableBody.innerHTML =
                                `<tr><td colspan="6" class="text-center text-danger">Gagal memuat data transaksi</td></tr>`;
                        });
                }

                searchBtn.addEventListener('click', function() {
                    currentSearch = searchInput.value;
                    currentTanggal = searchTanggal.value;
                    currentPage = 1;
                    loadTransaksi(currentPage, currentSearch, currentTanggal);
                });
                searchInput.addEventListener('keyup', function(e) {
                    if (e.key === 'Enter') {
                        currentSearch = searchInput.value;
                        currentTanggal = searchTanggal.value;
                        currentPage = 1;
                        loadTransaksi(currentPage, currentSearch, currentTanggal);
                    }
                });
                searchTanggal.addEventListener('change', function() {
                    currentTanggal = searchTanggal.value;
                    currentPage = 1;
                    loadTransaksi(currentPage, currentSearch, currentTanggal);
                });
                prevPageBtn.addEventListener('click', function() {
                    if (currentPage > 1) {
                        currentPage--;
                        loadTransaksi(currentPage, currentSearch, currentTanggal);
                    }
                });
                nextPageBtn.addEventListener('click', function() {
                    if (currentPage < lastPage) {
                        currentPage++;
                        loadTransaksi(currentPage, currentSearch, currentTanggal);
                    }
                });

                // Initial load
                loadTransaksi();
            });
        </script>
    @endpush
@endsection

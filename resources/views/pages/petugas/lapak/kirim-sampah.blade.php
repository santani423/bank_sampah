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

            .form-control,
            .form-select {
                border-radius: 10px;
                border: 1px solid #e2e8f0;
                padding: 0.6rem 1rem;
                transition: all 0.2s;
            }

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

            .table-bordered-custom td,
            .table-bordered-custom th {
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

            .preview-container img,
            .preview-mini img {
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

            .btn-save-final {
                background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
                color: white;
                border: none;
                padding: 12px 40px;
                border-radius: 12px;
                font-weight: 700;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                transition: all 0.3s;
            }

            .btn-save-final:hover {
                transform: translateY(-2px);
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
                            <label class="text-muted small d-block" for="nama_lapak">Nama Lapak</label>
                            <span id="nama_lapak" class="fw-bold">{{ $lapak->nama_lapak }}</span>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted small d-block" for="kode_lapak">Kode Lapak</label>
                            <span id="kode_lapak"
                                class="badge bg-primary bg-opacity-10 text-primary px-2">{{ $lapak->kode_lapak }}</span>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted small d-block" for="cabang_lapak">Cabang</label>
                            <span id="cabang_lapak"
                                class="fw-bold text-dark">{{ $lapak->cabang->nama_cabang ?? '-' }}</span>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted small d-block" for="lokasi_kota">Lokasi Kota</label>
                            <span id="lokasi_kota" class="fw-bold text-dark">{{ $lapak->kota ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <form id="mainDeliveryForm" enctype="multipart/form-data">
                    <input type="hidden" name="kode_lapak" value="{{ $lapak->kode_lapak }}">
                    <input type="hidden" name="petugas_id" value="{{ $petugas->id }}">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label" for="kode_pengiriman">Kode Pengiriman</label>
                            <input type="text" id="kode_pengiriman" class="form-control bg-light fw-bold"
                                name="kode_pengiriman" value="{{ $kodePengiriman }}" readonly required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="tanggal_pengiriman">Tanggal Kirim</label>
                            <input type="date" id="tanggal_pengiriman" class="form-control" name="tanggal_pengiriman"
                                value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="gudang_id">Gudang Tujuan</label>
                            <select id="gudang_id" name="gudang_id" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Customer --</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->nama_gudang }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="driver">Nama Driver / Supir</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-person-badge"></i></span>
                                <input type="text" id="driver" name="driver" class="form-control"
                                    placeholder="Nama pengemudi" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="driver_hp">No. Telepon Driver</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-whatsapp"></i></span>
                                <input type="tel" id="driver_hp" name="driver_hp" class="form-control"
                                    placeholder="08xxxx" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="plat_nomor">No. Plat Kendaraan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-truck"></i></span>
                                <input type="text" id="plat_nomor" name="plat_nomor" class="form-control"
                                    placeholder="B 1234 ABC" required>
                            </div>
                        </div>

                        <div class="col-md-6"> 
                            <label class="form-label" for="foto_sampah">Foto Sampah (Muatan)</label>
                            <div class="preview-container mb-2" id="preview-container-sampah">
                                <div class="preview-placeholder"><i class="bi bi-camera fs-1 d-block"></i>Pratinjau Foto
                                </div>
                            </div>
                            <input type="file" id="foto_sampah" name="foto_sampah" class="form-control"
                                accept="image/*" onchange="previewImage(this, 'preview-container-sampah')" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="foto_plat">Foto Plat Nomor Mobil</label>
                            <div class="preview-container mb-2" id="preview-container-plat">
                                <div class="preview-placeholder"><i class="bi bi-truck-flatbed fs-1 d-block"></i>Pratinjau
                                    Plat</div>
                            </div>
                            <input type="file" id="foto_plat" name="foto_plat" class="form-control" accept="image/*"
                                onchange="previewImage(this, 'preview-container-plat')" required>
                        </div>

                         
                    </div>
                </form>
            </div>
        </div>

        {{-- LIST TRANSAKSI --}}
        <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
            <h4 class="fw-bold text-primary-dark mb-0">Daftar Pengiriman (Halaman Ini)</h4>
            <div class="d-flex gap-2">
                <input type="hidden" id="searchTransaksi"  class="form-control form-control-sm"
                    placeholder="Cari Kode..." style="width: 150px;">
                    <div id="searchBtn"></div> 
            </div>
        </div>

        <div id="transaksiLapakList" class="row g-4"></div>

        {{-- PAGINATION --}}
        <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
            <p id="paginationInfo" class="text-muted small mb-0"></p>
            <div class="btn-group">
                <button id="prevPage" class="btn btn-outline-secondary btn-sm px-3"> </button>
                <button id="nextPage" class="btn btn-outline-secondary btn-sm px-3"> </button>
            </div>
        </div>

        {{-- REKAP TABEL --}}
        <div id="rekapSampahTable" class="mt-5 mb-5"></div>

        {{-- TOMBOL SIMPAN AKHIR --}}
        <div class="card border-0 bg-transparent mb-5">
            <div class="card-body p-0 text-center">
                <div class="alert alert-info border-0 shadow-sm rounded-4 mb-4">
                    <i class="bi bi-info-circle-fill me-2"></i> Pastikan semua data pengiriman di atas sudah benar sebelum
                    menyelesaikan sesi ini.
                </div>
                <button type="button" id="btnSaveFinal" class="btn btn-save-final">
                    <i class="bi bi-cloud-check-fill me-2"></i> SELESAI & KEMBALI KE DASHBOARD
                </button>
            </div>
        </div>
    </div>

   



    @push('scripts')
         

        <script>
            // Fungsi Pratinjau Gambar
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

            document.addEventListener('DOMContentLoaded', function() {
                const lapakId = @json($lapak->id);
                const list = document.getElementById('transaksiLapakList');
                const searchInput = document.getElementById('searchTransaksi');
                const searchBtn = document.getElementById('searchBtn');
                const prevBtn = document.getElementById('prevPage');
                const nextBtn = document.getElementById('nextPage');
                const info = document.getElementById('paginationInfo');
                const deliveryForm = document.getElementById('mainDeliveryForm');

                let page = 1;
                let lastPage = 1;
                let limit = 4;

                // 1. Fungsi Load Data Riwayat via API
                function loadData() {
                    list.innerHTML =
                        `<div class="col-12 text-center py-5"><div class="spinner-border text-primary"></div></div>`;
                    let url = `/api/lapak/${lapakId}/transaksi?page=${page}&limit=${limit}`;
                    if (searchInput.value) url += `&search=${searchInput.value}`;

                    fetch(url)
                        .then(res => res.json())
                        .then(res => {
                            const data = res.data || res.data.data;
                            lastPage = res.last_page || res.meta?.last_page || 1;
                            // info.textContent = `Halaman ${page} dari ${lastPage}`;
                            info.textContent = ` `;
                            list.innerHTML = '';

                            updateRekap(data);

                            if (!data || data.length === 0) {
                                list.innerHTML =
                                    `<div class="col-12 text-center py-5 text-muted">Belum ada riwayat pengiriman hari ini.</div>`;
                                return;
                            }

                            data.forEach(trx => {
                                let statusColor = trx.status === 'selesai' ? 'bg-success text-white' :
                                    'bg-warning text-dark';
                                let detailRows = '';

                                if (trx.detail_transaksi) {
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
                                }

                                list.innerHTML += `
                    <div class="col-12">
                        <div class="card transaksi-card border shadow-sm">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-primary"><i class="bi bi-hash"></i> ${trx.kode_transaksi}</span>
                                <span class="badge ${statusColor}">${trx.status.toUpperCase()}</span>
                            </div>
                            <div class="card-body">
                                 
                                <div class="table-responsive">
                                    <table class="table table-bordered-custom responsive-card-table mb-0">
                                        <thead><tr><th>#</th><th>Jenis</th><th>Berat</th><th>Harga</th><th class="text-end">Total</th></tr></thead>
                                        <tbody>${detailRows || '<tr><td colspan="5" class="text-center">Belum ada item</td></tr>'}</tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top py-3 text-end">
                                <h6 class="mb-0 fw-bold text-success">Total Transaksi: Rp ${Number(trx.total_transaksi || 0).toLocaleString('id-ID')}</h6>
                            </div>
                        </div>
                    </div>`;
                            });
                        });
                }

                // 2. Submit Form via AJAX (Full Upload Gambar)
                deliveryForm.onsubmit = function(e) {
                    e.preventDefault();
                    const btn = document.getElementById('btnSubmitTemp');
                    const formData = new FormData(this);

                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Mengunggah...';

                    fetch("{{ route('petugas.lapak.proses-kirim-sampah', $lapak->id) }}", {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                            }
                        })
                        .then(res => res.json())
                        .then(res => {
                            btn.disabled = false;
                            btn.innerHTML = '<i class="bi bi-plus-circle me-2"></i>Tambah Ke Riwayat Sementara';

                            if (res.status === 'success') {
                                alert('Berhasil: ' + res.message);
                                deliveryForm.reset();
                                document.getElementById('preview-container-sampah').innerHTML =
                                    '<div class="preview-placeholder">Pratinjau Foto</div>';
                                document.getElementById('preview-container-plat').innerHTML =
                                    '<div class="preview-placeholder">Pratinjau Plat</div>';
                                loadData(); // Reload riwayat
                            } else {
                                alert('Gagal: ' + res.message);
                            }
                        })
                        .catch(err => {
                            btn.disabled = false;
                            btn.innerHTML = '<i class="bi bi-plus-circle me-2"></i>Tambah Ke Riwayat Sementara';
                            alert('Terjadi kesalahan sistem/jaringan.');
                        });
                };

                // 3. Update Rekapitulasi Tabel
                function updateRekap(data) {
                    let rekap = {};
                    if (data) {
                        data.forEach(trx => {
                            trx.detail_transaksi?.forEach(item => {
                                let nama = item.sampah ? item.sampah.nama_sampah : '-';
                                rekap[nama] = (rekap[nama] || 0) + (parseFloat(item.berat_kg) || 0);
                            });
                        });
                    }

                    let rekapHtml = '';
                    if (Object.keys(rekap).length > 0) {
                        rekapHtml = `
                <div class="card border-0 bg-dark shadow overflow-hidden">
                    <div class="card-header bg-dark border-0"><h6 class="text-white mb-0">Ringkasan Berat (Halaman Ini)</h6></div>
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

                // Navigasi & Kontrol
                searchBtn.onclick = () => {
                    page = 1;
                    loadData();
                }
                prevBtn.onclick = () => {
                    if (page > 1) {
                        page--;
                        loadData();
                    }
                }
                nextBtn.onclick = () => {
                    if (page < lastPage) {
                        page++;
                        loadData();
                    }
                }

                // Simpan Akhir & Redirect
                document.getElementById('btnSaveFinal').onclick = function() {
                    // if (confirm('Pastikan semua data sudah benar. Kirim data dan kembali ke dashboard?')) {
                    const btn = this;
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';
                    // Ambil seluruh input dari form utama, termasuk file
                    const formData = new FormData(deliveryForm);
                    fetch(`/api/lapak/${lapakId}/finalisasi`, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                                // Jangan set Content-Type, biarkan browser mengatur multipart/form-data
                            },
                            body: formData
                        })
                        .then(res => res.json())
                        .then(res => {
                            btn.disabled = false;
                            btn.innerHTML =
                                '<i class="bi bi-cloud-check-fill me-2"></i> SELESAI & KEMBALI KE DASHBOARD';
                            if (res.status === 'success') {
                                alert('Berhasil: ' + res.message);
                                // window.location.href = "{{ route('petugas.lapak.index') }}";
                            } else {
                                alert('Gagal: ' + (res.message || 'Tidak dapat menyimpan.'));
                            }
                        })
                        .catch(() => {
                            btn.disabled = false;
                            btn.innerHTML =
                                '<i class="bi bi-cloud-check-fill me-2"></i> SELESAI & KEMBALI KE DASHBOARD';
                            alert('Terjadi kesalahan sistem/jaringan.');
                        });
                    // }
                };

                // Initial Load
                loadData();
            });
        </script>
    @endpush

@endsection

@extends('layouts.template')

@section('title', 'Pengiriman Lapak')

@push('style')
    <style>
        #loading-spinner {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
        }

        .pagination-controls button {
            margin: 0 2px;
        }
    </style>
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Nasabah</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    {{-- ALERT --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- FILTER FORM --}}
                    <form id="filter-form">
                        <div class="row align-items-end">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Nasabah</label>
                                <input type="text" class="form-control" id="nasabah" placeholder="Nama nasabah">
                            </div>
                            <div class="col-md-3 mb-3">
                                <x-select.select-cabang name="cabang" />
                            </div>




                            <div class="col-md-12 mb-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Cari
                                </button>

                                <a href="{{ url()->current() }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    {{-- TABLE --}}
                    <div class="table-responsive">
                        <div id="loading-spinner">
                            <div class="spinner-border" role="status"></div>
                        </div>


                        <table class="table table-hover table-bordered table-head-bg-primary text-nowrap" id="petugas-table"
                            style="display:none;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th style="width: 250px">Aksi</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Nama Nasabah</th>
                                    <th>Jumlah Penarikan</th>
                                </tr>
                            </thead>
                            <tbody id="petugas-tbody"></tbody>
                        </table>

                        <!-- Modal Konfirmasi Setujui -->
                        <div class="modal fade" id="modalSetujui" tabindex="-1" aria-labelledby="modalSetujuiLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form id="formSetujui" method="POST" action="{{ route('admin.tarik-saldo.setujui') }}">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalSetujuiLabel">Konfirmasi Setujui</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Apakah Anda yakin ingin menyetujui pencairan saldo <span id="setujuiNamaNasabah" class="fw-bold"></span> sebesar <span id="setujuiJumlah" class="fw-bold"></span>?
                                            <input type="text" name="id" id="setujuiId">
                                            <input type="text" name="jumlah_pencairan" id="setujuiJumlahInput">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-success" id="btnKonfirmasiSetujui">Setujui</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Konfirmasi Tolak -->
                        <div class="modal fade" id="modalTolak" tabindex="-1" aria-labelledby="modalTolakLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalTolakLabel">Konfirmasi Tolak</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah Anda yakin ingin menolak pencairan saldo <span id="tolakNamaNasabah" class="fw-bold"></span> sebesar <span id="tolakJumlah" class="fw-bold"></span>?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="button" class="btn btn-danger" id="btnKonfirmasiTolak">Tolak</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="pagination-wrapper" class="pagination-wrapper" style="display:none;">
                            <div id="pagination-info"></div>
                            <div id="pagination-controls"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div> 
@endsection

@push('scripts')
    <script>
        /* ===============================
                                                                                                                                           AMBIL FILTER TANGGAL
                                                                                                                                        ================================ */
        function getFilterParams() {

            const nasabah = document.getElementById('nasabah').value;
            const cabang = document.getElementById('cabang').value;



            return {
                nasabah: nasabah,
                cabang: cabang,
            };
        }


        /* ===============================
           FETCH DATA API
        ================================ */
        function fetchPetugasData(page = 1) {
            const spinner = document.getElementById('loading-spinner');
            const table = document.getElementById('petugas-table');
            const pagination = document.getElementById('pagination-wrapper');

            spinner.style.display = 'block';
            table.style.display = 'none';
            pagination.style.display = 'none';

            const filters = getFilterParams();
            const params = new URLSearchParams({
                page,
                per_page: perPage,

                ...(filters.nasabah && {
                    search: filters.nasabah,
                }),
                ...(filters.cabang && {
                    cabang: filters.cabang,
                }),
            });





            fetch(`/api/pencairan-nasabah-list?${params.toString()}`)
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        current_page = res.pagination.current_page;
                        totalPages = res.pagination.last_page;
                        renderTable(res.data, res.pagination);
                        renderPagination(res.pagination);

                        spinner.style.display = 'none';
                        table.style.display = 'table';
                        pagination.style.display = 'flex';
                    }
                })
                .catch(() => {
                    spinner.innerHTML = '<div class="alert alert-danger">Gagal memuat data</div>';
                });
        }

        /* ===============================
           RENDER TABLE
        ================================ */
        function renderTable(data, pagination) {
            const tbody = document.getElementById('petugas-tbody');
            tbody.innerHTML = '';

            if (!data.length) {
                tbody.innerHTML = `<tr><td colspan="10" class="text-center">Tidak ada data</td></tr>`;
                return;
            }
 

            data.forEach((item, index) => {
                const no = pagination.from + index;  

                tbody.innerHTML += `
            <tr>
                <td>${no}</td> 
                <td>
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalSetujui"
                        onclick="showSetujuiModal('${item?.nasabah?.nama_lengkap}', '${item?.jumlah_pencairan}', '${item?.kode_pengiriman}')">
                        Setujui
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalTolak"
                        onclick="showTolakModal('${item?.nasabah?.nama_lengkap}', '${item?.jumlah_pencairan}', '${item?.kode_pengiriman}')">
                        Tolak
                    </button>
                </td>
                <td>${item?.created_at}</td> 
                <td>${item?.nasabah?.nama_lengkap}</td> 
                <td>${item?.jumlah_pencairan}</td> 
            </tr>
        `;
            });
        }

        // Fungsi format rupiah
        function formatRupiah(angka) {
            if (angka == null) return '-';
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }



        /* ===============================
           EVENT
        ================================ */
        document.getElementById('filter-form').addEventListener('submit', function(e) {
            e.preventDefault();
            fetchPetugasData(1);
        });

        document.addEventListener('DOMContentLoaded', () => {
            fetchPetugasData(1);
        });
        // Fungsi untuk menampilkan modal Setujui dengan data dinamis
        function showSetujuiModal(nama, jumlah, kode) {
            document.getElementById('setujuiNamaNasabah').textContent = nama;
            document.getElementById('setujuiJumlah').textContent = formatRupiah(jumlah);
            document.getElementById('setujuiId').value = kode;
            document.getElementById('setujuiJumlahInput').value = jumlah;
        }

        // Fungsi untuk menampilkan modal Tolak dengan data dinamis
        function showTolakModal(nama, jumlah, kode) {
            document.getElementById('tolakNamaNasabah').textContent = nama;
            document.getElementById('tolakJumlah').textContent = formatRupiah(jumlah);
            document.getElementById('btnKonfirmasiTolak').onclick = function() {
                // TODO: Aksi konfirmasi tolak, misal AJAX atau submit form
                // kode_pengiriman: kode
                // Tutup modal setelah aksi
                var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalTolak'));
                modal.hide();
            };
        }
    </script>
@endpush

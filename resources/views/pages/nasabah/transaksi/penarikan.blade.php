@extends('layouts.template')

@section('title', 'Penarikan Saldo Nasabah')

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
            <h3 class="fw-bold mb-3">Penarikan Saldo Nasabah</h3>
        </div>
    </div>


    <!-- Fitur Tambah Penarikan Saldo -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Metode Penarikan</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPenarikan">
                + Tambah Penarikan
            </button>
        </div>
    </div>

    <!-- Modal Penarikan -->
    <div class="modal fade" id="modalPenarikan" tabindex="-1" aria-labelledby="modalPenarikanLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formPenarikan" action="{{ route('api.nasabah.requestWithdrawal') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalPenarikanLabel">Tambah Metode Penarikan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label d-block">Pilih Jumlah Penarikan</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ([10000, 20000, 50000, 100000, 150000] as $jumlah)
                                    <button type="button" class="btn btn-primary btn-jumlah" data-jumlah="{{ $jumlah }}">
                                        Rp {{ number_format($jumlah, 0, ',', '.') }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="manual_jumlah" class="form-label">Atau masukkan jumlah secara manual</label>
                            <input type="text" class="form-control" id="manual_jumlah" placeholder="Masukkan jumlah manual (contoh: 75.000)" autocomplete="off">
                            <input type="hidden" name="jumlah_pencairan" id="jumlah_pencairan" required>
                            <small class="text-muted">
                                Minimal penarikan: <strong>Rp {{ number_format($minPenarikan ?? 10000, 0, ',', '.') }}</strong>
                            </small>
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="metode" class="form-label">Metode</label>
                            <select name="metode_pencairan_id" id="metode" class="form-select" required>
                                <option value="">Pilih Metode Penarikan</option>
                                @foreach ($metodePenarikan ?? [] as $metode)
                                    <option value="{{ $metode->id }}">{{ $metode->jenisMetodePenarikan->nama ?? '' }} - {{ $metode->nama_metode_pencairan ?? '' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary w-100">Kirim Penarikan</button>
                    </div>
                </div>
            </form>
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
                                <label class="form-label">Dari Tanggal</label>
                                <input type="date" class="form-control" id="tanggal_dari" name="tanggal_dari">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Sampai Tanggal</label>
                                <input type="date" class="form-control" id="tanggal_sampai" name="tanggal_sampai">
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
                                    <th>#</th>
                                    <th>Aksi</th>
                                    <th>Tanggal</th>
                                    <th>Jumlah Penarikan</th>
                                    <th>Metode Penarikan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="petugas-tbody"></tbody>
                        </table>

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
document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.btn-jumlah');
    const inputHidden = document.getElementById('jumlah_pencairan');
    const inputManual = document.getElementById('manual_jumlah');
    const form = document.getElementById('formPenarikan');
    const minPenarikan = {{ $minPenarikan ?? 10000 }};

    function formatRupiah(angka) {
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    if(buttons && inputHidden && inputManual) {
        buttons.forEach(button => {
            button.addEventListener('click', () => {
                const val = parseInt(button.dataset.jumlah);
                inputHidden.value = val;
                inputManual.value = formatRupiah(val);
            });
        });

        inputManual.addEventListener('input', function () {
            let val = this.value.replace(/[^\d]/g, '');
            if (val) {
                this.value = formatRupiah(val);
                inputHidden.value = val;
            } else {
                this.value = '';
                inputHidden.value = '';
            }
        });
    }

    if(form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const jumlah = parseFloat(inputHidden.value || 0);
            if (jumlah < minPenarikan) {
                alert(`Jumlah penarikan minimal adalah Rp ${minPenarikan.toLocaleString('id-ID')}`);
                return;
            }
            const formData = new FormData(form);
            $.ajax({
                url: form.action,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                success: function(res) {
                    alert('Penarikan berhasil diajukan!');
                    var modal = bootstrap.Modal.getInstance(document.getElementById('modalPenarikan'));
                    if (modal) modal.hide();
                    form.reset();
                    inputManual.value = '';
                    inputHidden.value = '';
                    location.reload();
                },
                error: function(xhr) {
                    let msg = 'Terjadi kesalahan. Mohon coba lagi.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    alert(msg);
                }
            });
        });
    }
});
</script>
    <script>
        /* ===============================
                                                                                                                                                                   AMBIL FILTER TANGGAL
                                                                                                                                                                ================================ */
        function getFilterParams() {
            const tanggalDari = document.getElementById('tanggal_dari').value;
            const tanggalSampai = document.getElementById('tanggal_sampai').value;
            return {
                tanggal_dari: tanggalDari,
                tanggal_sampai: tanggalSampai,
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
                ...(filters.tanggal_dari && {
                    tanggal_dari: filters.tanggal_dari
                }),
                ...(filters.tanggal_sampai && {
                    tanggal_sampai: filters.tanggal_sampai
                })
            });
            fetch(`/api/nasabah/withdrawalList?${params.toString()}`)
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        current_page = res.pagination.current_page;
                        totalPages = res.pagination.last_page;
                        renderTable(res.data, res.pagination);
                        renderPagination(res.pagination);

                        spinner.style.display = 'none';
                        ""
                        table.style.display = 'table';
                        pagination.style.display = 'flex';
                    }
                })
                .catch(() => {
                    spinner.innerHTML = '<div class="alert alert-danger">Gagal memuat data</div>';
                });
        }

        // Fungsi format rupiah
        function formatRupiah(angka) {
            if (angka == null) return '-';
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
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

            const detailRoute = "{{ route('admin.nasabah.show', ':kode') }}";
            const detailEdit = "{{ route('admin.nasabah.edit', ':kode') }}";

            data.forEach((item, index) => {
                const no = pagination.from + index;
                console.log("item", item);
                let tanggal = '-';
                if (item?.created_at) {
                    const dateObj = new Date(item.created_at);
                    const pad = n => n.toString().padStart(2, '0');
                    tanggal =
                        `${dateObj.getFullYear()}-${pad(dateObj.getMonth()+1)}-${pad(dateObj.getDate())} ${pad(dateObj.getHours())}:${pad(dateObj.getMinutes())}`;
                }
                const jumlahRupiah = formatRupiah(item?.jumlah_pencairan);
                tbody.innerHTML += `
            <tr>
                <td>${no}</td>
                <td>
                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalSetujui"
                        >
                        Detail
                    </button> 
                </td>
                <td>${tanggal}</td>
                <td>${item?.jumlah_pencairan}</td>
                <td>${item?.metode_pencairan?.jenis_metode_penarikan?.nama}</td>
                <td>${item?.status}</td>
               
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
    </script>
@endpush

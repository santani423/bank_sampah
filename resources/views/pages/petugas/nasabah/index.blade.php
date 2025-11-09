@extends('layouts.template')

@section('title', 'Nasabah')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Nasabah</h3>
            {{-- <h6 class="op-7 mb-2">Anda dapat mengelola semua nasabah, seperti mengedit, menghapus, dan lainnya.</h6> --}}
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <div class="section-header-button">
                {{-- <a href="{{ route('petugas.nasabah.create') }}" class="btn btn-primary btn-round">Tambah Nasabah Baru</a> --}}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-5">
                <div class="card-body">

                    <div class="float-right">
                        <form id="searchForm" onsubmit="return false;">
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchInput" placeholder="Cari Nama atau No. Registrasi">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" id="searchBtn">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card">

                <div class="card-body">

                    <div class="clearfix mb-3"></div>
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div id="nasabah-container">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-head-bg-primary">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="width: 250px">Aksi</th>
                                        <th>Nama</th>
                                        <th>No. Registrasi</th>
                                        <th>No. HP</th>
                                        <th>Cabang</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="nasabahTableBody"></tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div id="nasabah-summary" class="text-muted small"></div>
                            <nav id="nasabah-pagination" aria-label="Pagination"></nav>
                        </div>
                        <div id="loading-overlay" class="loading-overlay d-none">
                            <div class="spinner">
                                <div class="spinner-border text-primary" role="status" aria-hidden="true"></div>
                                <div class="small text-muted">Memuat data...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <style>
        #nasabah-container { position: relative; }
        .loading-overlay { position: absolute; inset:0; background: rgba(255,255,255,.7); display:flex; align-items:center; justify-content:center; z-index:10; }
        .loading-overlay .spinner { display:inline-flex; flex-direction:column; align-items:center; gap:.5rem; }
        #nasabah-pagination .pagination { gap:.5rem; }
        #nasabah-pagination .page-link { border-radius:.75rem; border:1px solid #e5e7eb; color:#6b7280; padding:.375rem .75rem; background:#fff; }
        #nasabah-pagination .page-item.active .page-link { background:#eef4ff; color:#3b82f6; border-color:#dbeafe; box-shadow:inset 0 0 0 2px rgba(59,130,246,.08); }
        #nasabah-pagination .page-item.disabled .page-link { color:#cbd5e1; background:#f8fafc; border-color:#f1f5f9; }
        #nasabah-pagination .page-link:focus { box-shadow:none; }
    </style>
    <script>
        let currentPage = 1;
        let currentSearch = '';
        const petugasEmail = `{{ auth()->check() ? auth()->user()->email : '' }}`;

        function fetchNasabah(page = 1, search = '') {
            const overlay = document.getElementById('loading-overlay');
            overlay.classList.remove('d-none');
            const tbody = document.getElementById('nasabahTableBody');
            tbody.innerHTML = '';
            // Build URL with new expected query params: nama_nasabah & no_registrasi (controller supports both separately)
            const baseUrl = `{{ url('/api/nasabah-petugas') }}`;
            const urlObj = new URL(baseUrl, window.location.origin);
            urlObj.searchParams.set('page', page);
            if (petugasEmail) {
                urlObj.searchParams.set('email', petugasEmail);
            }
            if (search) {
                // Send search term to both filters so backend can match either field
                urlObj.searchParams.set('search', search); 
            }
            fetch(urlObj.toString(), {headers:{'Accept':'application/json'}})
                .then(r => r.json())
                .then(res => {
                    let rows='';
                    let no = (res.current_page - 1) * res.per_page + 1;
                    (res.data||[]).forEach(n => {
                        rows += `<tr>
                            <td>${no++}</td>
                            <td>
                                <a href='/petugas/nasabah/${n.id}' class='btn btn-info btn-sm mb-1'>Detail</a>
                                <a href='/petugas/transaksi/create?no_registrasi=${n.no_registrasi}' class='btn btn-warning btn-sm mb-1'>Setoran</a>
                            </td>
                            <td>${n.nama_lengkap ?? '-'}</td>
                            <td>${n.no_registrasi ?? '-'}</td>
                            <td>${n.no_hp ?? '-'}</td>
                            <td>${n.nama_cabang ?? '-'}</td>
                            <td>${n.status === 'aktif' ? 'Aktif' : 'Tidak Aktif'}</td>
                        </tr>`;
                    });
                    tbody.innerHTML = rows || '<tr><td colspan="7" class="text-center">Tidak ada data</td></tr>';
                    renderPagination(res);
                    const from = res.data && res.data.length ? ((res.current_page - 1) * res.per_page) + 1 : 0;
                    const to = ((res.current_page - 1) * res.per_page) + (res.data ? res.data.length : 0);
                    document.getElementById('nasabah-summary').textContent = `Showing ${from} to ${to} of ${res.total ?? to} entries`;
                })
                .catch(() => {
                    tbody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Gagal memuat data</td></tr>';
                    document.getElementById('nasabah-pagination').innerHTML='';
                    document.getElementById('nasabah-summary').textContent='';
                })
                .finally(()=> overlay.classList.add('d-none'));
        }

        function renderPagination(res){
            const nav = document.getElementById('nasabah-pagination');
            if(!res || res.last_page <= 1){ nav.innerHTML=''; return; }
            let html = '<ul class="pagination mb-0">';
            html += `<li class="page-item${res.current_page===1?' disabled':''}"><a class="page-link" href="#" data-page="${res.current_page-1}" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>`;
            let start = Math.max(1, res.current_page - 2);
            let end = Math.min(res.last_page, res.current_page + 2);
            if(start>1){ html += `<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`; if(start>2) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`; }
            for(let i=start;i<=end;i++){ html += `<li class="page-item${i===res.current_page?' active':''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`; }
            if(end<res.last_page){ if(end<res.last_page-1) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`; html += `<li class="page-item"><a class="page-link" href="#" data-page="${res.last_page}">${res.last_page}</a></li>`; }
            html += `<li class="page-item${res.current_page===res.last_page?' disabled':''}"><a class="page-link" href="#" data-page="${res.current_page+1}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>`;
            html += '</ul>';
            nav.innerHTML = html;
        }

        document.addEventListener('click', e => {
            if(e.target.closest('.pagination .page-link')){
                e.preventDefault();
                const page = e.target.closest('.page-link').dataset.page;
                if(!page) return;
                currentPage = parseInt(page);
                fetchNasabah(currentPage, currentSearch);
            }
        });

        document.getElementById('searchBtn').addEventListener('click', () => {
            currentSearch = document.getElementById('searchInput').value.trim();
            currentPage = 1; fetchNasabah(currentPage, currentSearch);
        });
        document.getElementById('searchInput').addEventListener('keyup', e => { if(e.key==='Enter'){ document.getElementById('searchBtn').click(); }});

        fetchNasabah();
    </script>
@endpush

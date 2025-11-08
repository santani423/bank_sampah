@extends('layouts.template')

@section('title', 'Nasabah')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Rekanan</h3>
            {{-- <h6 class="op-7 mb-2">Anda dapat mengelola semua nasabah, seperti mengedit, menghapus, dan lainnya.</h6> --}}
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <div class="section-header-button">
                <a href="{{ route('petugas.rekanan.create') }}" class="btn btn-primary btn-round">Tambah Nasabah Baru</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-5">
                <div class="card-body">

                    <div class="float-right">
                        <form method="GET" action="{{ route('petugas.rekanan.index') }}">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Cari Nama" name="nama_nasabah"
                                    value="{{ request('nama_nasabah') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary">Search</button>
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
                            <tbody>
                               
                            </tbody>
                        </table>

                    </div>
                    <div class="float-right">
                         
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let currentPage = 1;
    let currentSearch = '';

    function fetchNasabah(page = 1, search = '') {
        $.ajax({
            url: `/api/nasabah-badan?page=${page}&search=${search}`,
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Bearer {{ auth()->user() ? auth()->user()->createToken('token')->plainTextToken : '' }}'
            },
            success: function(res) {
                let rows = '';
                let no = (res.current_page - 1) * res.per_page + 1;
                res.data.forEach(function(nasabah) {
                    rows += `<tr>
                        <td>${no++}</td>
                        <td>
                            <a href='/petugas/rekanan/${nasabah.id}' class='btn btn-info btn-sm'>Detail</a>
                            <a href='/petugas/rekanan/${nasabah.id}/edit' class='btn btn-warning btn-sm'>Edit</a>
                        </td>
                        <td>${nasabah.nama_badan}</td>
                        <td>${nasabah.nib ?? '-'}</td>
                        <td>${nasabah.no_telp ?? '-'}</td>
                        <td>${nasabah.jenis_badan ? nasabah.jenis_badan.nama : '-'}</td>
                        <td>${nasabah.status}</td>
                    </tr>`;
                });
                if (rows === '') rows = '<tr><td colspan="7" class="text-center">Tidak ada data</td></tr>';
                $(".table tbody").html(rows);
                // Pagination
                let pagination = '';
                if (res.last_page > 1) {
                    pagination += '<nav><ul class="pagination">';
                    for (let i = 1; i <= res.last_page; i++) {
                        pagination += `<li class="page-item${i === res.current_page ? ' active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                    }
                    pagination += '</ul></nav>';
                }
                $(".float-right").html(pagination);
            }
        });
    }

    // Initial fetch
    fetchNasabah();

    // Pagination click
    $(document).on('click', '.pagination .page-link', function(e) {
        e.preventDefault();
        let page = $(this).data('page');
        currentPage = page;
        fetchNasabah(page, currentSearch);
    });

    // Search
    $(document).on('submit', 'form', function(e) {
        e.preventDefault();
        let search = $(this).find('input[name="nama_nasabah"]').val();
        currentSearch = search;
        fetchNasabah(1, search);
    });
});
</script>
@endpush

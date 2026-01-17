@extends('layouts.template')

@section('title', 'Petugas')

@section('main')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">Manajemen Petugas</h3>
    </div>
    <div class="ms-md-auto py-2 py-md-0">
        <a href="{{ route('admin.petugas.create') }}" class="btn btn-primary btn-round">
            Tambah Petugas
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">

                    <!-- LOADING -->
                    <div id="loading-spinner" class="text-center py-5">
                        <div class="spinner-border" role="status"></div>
                    </div>

                    <!-- TABLE -->
                    <table class="table table-hover table-bordered table-head-bg-primary text-nowrap"
                        id="petugas-table" style="display:none;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Aksi</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Username</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody id="petugas-tbody"></tbody>
                    </table>

                    <!-- PAGINATION -->
                    <div id="pagination-wrapper" class="d-flex justify-content-between align-items-center mt-3"
                        style="display:none;">
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
let currentPage = 1;
let perPage = 10;

document.addEventListener("DOMContentLoaded", function () {
    fetchPetugasData();
});

function fetchPetugasData(page = 1) {
    const spinner = document.getElementById('loading-spinner');
    const table = document.getElementById('petugas-table');
    const pagination = document.getElementById('pagination-wrapper');

    spinner.style.display = 'block';
    table.style.display = 'none';
    pagination.style.display = 'none';

    fetch(`/api/petugas?page=${page}&per_page=${perPage}`)
        .then(res => {
            if (!res.ok) throw new Error('Gagal request');
            return res.json();
        })
        .then(res => {
            if (!res.success) throw new Error('Response error');

            renderTable(res.data, res.pagination);
            renderPagination(res.pagination);

            spinner.style.display = 'none';
            table.style.display = 'table';
            pagination.style.display = 'flex';
        })
        .catch(err => {
            console.error(err);
            spinner.innerHTML =
                `<div class="alert alert-danger">Gagal memuat data petugas</div>`;
        });
}

function renderTable(data, pagination) {
    const tbody = document.getElementById('petugas-tbody');
    tbody.innerHTML = '';

    if (!data || data.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center">Belum ada data petugas</td>
            </tr>`;
        return;
    }

    data.forEach((item, index) => {
        const nomor = pagination.from + index;

        tbody.insertAdjacentHTML('beforeend', `
            <tr>
                <td>${nomor}</td>
                <td>
                    <form action="/admin/data-petugas/${item.id}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                        <a href="/admin/data-petugas/${item.id}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="/admin/data-petugas/${item.id}/edit" class="btn btn-sm btn-primary">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <input type="hidden" name="_token"
                            value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
                <td>${item.nama}</td>
                <td>${item.email}</td>
                <td>${item.username}</td>
                <td>${item.role.charAt(0).toUpperCase() + item.role.slice(1)}</td>
            </tr>
        `);
    });
}

function renderPagination(pagination) {
    const info = document.getElementById('pagination-info');
    const controls = document.getElementById('pagination-controls');

    info.innerHTML = `Menampilkan ${pagination.from}–${pagination.to} dari ${pagination.total} data`;
    controls.innerHTML = '';

    const btn = (label, page, disabled = false, active = false) => {
        const b = document.createElement('button');
        b.className = `btn btn-sm mx-1 ${active ? 'btn-primary' : 'btn-outline-primary'}`;
        b.textContent = label;
        b.disabled = disabled;
        b.onclick = () => fetchPetugasData(page);
        return b;
    };

    controls.appendChild(
        btn('«', pagination.current_page - 1, pagination.current_page === 1)
    );

    for (let i = 1; i <= pagination.last_page; i++) {
        controls.appendChild(
            btn(i, i, false, i === pagination.current_page)
        );
    }

    controls.appendChild(
        btn('»', pagination.current_page + 1,
            pagination.current_page === pagination.last_page)
    );
}
</script>
@endpush

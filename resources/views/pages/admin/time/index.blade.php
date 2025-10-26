@extends('layouts.template')

@section('title', 'Anggota Tim')

@push('style')
    <style>
        .avatar-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
        }

        /* Warna teks dalam tabel */
        table.table td {
            color: #000; /* hitam */
            vertical-align: middle;
        }

        table.table th {
            color: #000;
        }

        /* Hover efek */
        table.table tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Anggota Tim</h3>
            <h6 class="op-7 mb-2">Anda dapat mengelola semua anggota tim, seperti menambah, mengedit, dan menghapus data.</h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <div class="section-header-button">
                <a href="{{ route('admin.time.create') }}" class="btn btn-primary btn-round">Tambah Anggota Tim</a>
            </div>
        </div>
    </div>

    {{-- ✅ ALERT MESSAGE --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Gagal!</strong> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">

            {{-- <div class="card">
                <div class="card-body">
                    <div class="float-right">
                        <form method="GET" action="{{ route('admin.time.index') }}">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Cari Nama Tim" name="name"
                                    value="{{ request('name') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary">Cari</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div> --}}

            <div class="card">
                <div class="card-body">
                    <div class="clearfix mb-3"></div>

                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Avatar</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Keterangan</th>
                                    <th>Dibuat Pada</th>
                                    <th style="width: 140px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($times as $index => $time)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        {{-- AVATAR --}}
                                        <td>
                                            @if ($time->avatar)
                                                <img src="{{ asset('storage/' . $time->avatar) }}" alt="{{ $time->name }}"
                                                    class="avatar-img">
                                            @else
                                                <img src="{{ asset('images/default-avatar.png') }}" alt="Default Avatar"
                                                    class="avatar-img">
                                            @endif
                                        </td>

                                        {{-- NAMA --}}
                                        <td>{{ $time->name }}</td>

                                        {{-- JABATAN --}}
                                        <td>{{ $time->jabatan ?? '-' }}</td>

                                        {{-- KETERANGAN --}}
                                        <td>{{ $time->keterangan ?? '-' }}</td>

                                        {{-- CREATED AT --}}
                                        <td>{{ $time->created_at ? $time->created_at->format('d M Y H:i') : '-' }}</td>

                                        {{-- AKSI --}}
                                        <td>
                                            <a href="{{ route('admin.time.edit', $time->id) }}"
                                                class="btn btn-sm btn-warning">Edit</a>

                                            <form action="{{ route('admin.time.destroy', $time->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data tim ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{-- PAGINATION --}}
                        <div class="mt-3">
                            {{ $times->links() }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
@endpush

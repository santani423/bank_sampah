@extends('layouts.template')

@section('title', 'Cabang')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Daftar Cabang</h3>
            <h6 class="op-7 mb-2">
                Anda dapat mengelola semua cabang, seperti mengedit, menghapus, dan lainnya.
            </h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <div class="section-header-button">
                <a href="{{ route('admin.cabang.create') }}" class="btn btn-primary btn-round">Tambah Cabang</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <div class="float-right">
                        <form method="GET" action="{{ route('admin.cabang.index') }}">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Cari Nama" name="name"
                                    value="{{ request('name') }}">
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
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-head-bg-primary">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>Kontak</th>
                                    <th style="width: 250px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cabangs as $cabang)
                                    <tr>
                                        <td>{{ $cabang->id }}</td>
                                        <td>{{ $cabang->nama_cabang }}</td>
                                        <td>{{ $cabang->alamat }}</td>
                                        <td>{{ $cabang->telepon }}</td>
                                        <td>
                                            <div class="d-flex justify-content-start">
                                                <a href="{{ route('admin.cabang.show', $cabang->id) }}"
                                                    class="btn btn-sm btn-info me-2">
                                                    <i class="fas fa-info-circle"></i> Detail
                                                </a>
                                                <a href="{{ route('admin.cabang.edit', $cabang->id) }}"
                                                    class="btn btn-sm btn-warning me-2"><i class="fas fa-pencil-alt"></i>
                                                    Edit</a>
                                                <form action="{{ route('admin.cabang.destroy', $cabang->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Anda yakin ingin menghapus cabang ini?')"><i
                                                            class="fas fa-trash"></i> Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <x-pagination :data="$cabangs" />
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <!-- JS Libraies -->

    <!-- Page Specific JS File -->
@endpush

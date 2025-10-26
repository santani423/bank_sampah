@extends('layouts.template')

@section('title', 'Daftar Clean')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Daftar Clean</h3>
            <h6 class="op-7 mb-2">
                Anda dapat mengelola semua data clean (sampah bersih), seperti mengedit, menghapus, dan lainnya.
            </h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <div class="section-header-button">
                <a href="{{ route('admin.cleans.create') }}" class="btn btn-primary btn-round">Tambah Clean</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="float-right">
                        <form method="GET" action="{{ route('admin.cleans.index') }}">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Cari Judul" name="title"
                                    value="{{ request('title') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary">Cari</button>
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
                            <thead class="thead-light">
                                <tr>
                                    <th width="60">#</th>
                                    <th>Judul</th>
                                    <th>Slug</th>
                                    <th>Deskripsi</th>
                                    <th>Gambar</th>
                                    <th>Status</th>
                                    <th width="200">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cleans as $clean)
                                    <tr>
                                        <td> </td>
                                        <td>{{ $clean->title }}</td>
                                        <td>{{ $clean->slug }}</td>
                                        <td>{{ Str::limit($clean->description, 50) }}</td>
                                        <td>
                                            @if ($clean->image)
                                                <img src="{{ asset('storage/' . $clean->image) }}" alt="{{ $clean->title }}"
                                                    width="80" class="rounded">
                                            @else
                                                <span class="text-muted">Tidak ada</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($clean->status === 'active')
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.cleans.edit', $clean->id) }}" class="btn btn-warning btn-sm">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.cleans.destroy', $clean->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada data clean.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                      
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
@endpush

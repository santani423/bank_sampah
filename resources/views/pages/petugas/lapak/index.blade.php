@extends('layouts.template')

@section('title', 'Data Lapak')

@push('style')
    <style>
        .table td,
        .table th {
            vertical-align: middle;
        }

        .badge {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .pagination {
            margin: 0;
        }

        .pagination .page-item .page-link {
            padding: 0.5rem 0.75rem;
            border-radius: 0.25rem;
            margin: 0 0.125rem;
            color: #1572e8;
            border: 1px solid #dee2e6;
        }

        .pagination .page-item.active .page-link {
            background-color: #1572e8;
            border-color: #1572e8;
            color: #fff;
        }

        .pagination .page-item:hover:not(.active):not(.disabled) .page-link {
            background-color: #e9ecef;
            color: #1572e8;
        }

        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #fff;
            border-color: #dee2e6;
        }
    </style>
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Data Lapak</h3>
            <h6 class="op-7 mb-2">Kelola data lapak milik nasabah</h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <a href="{{ route('petugas.lapak.create') }}" class="btn btn-primary btn-round">
                <i class="fas fa-plus"></i> Tambah Lapak
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <!-- Filter Card -->
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" action="{{ route('petugas.lapak.index') }}">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Nama Lapak</label>
                                    <input type="text" name="nama_lapak" class="form-control"
                                        placeholder="Cari nama lapak..." value="{{ request('nama_lapak') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="">Semua Status</option>
                                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif
                                        </option>
                                        <option value="tidak_aktif"
                                            {{ request('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Filter
                                        </button>
                                        <a href="{{ route('petugas.lapak.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-redo"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Data Table Card -->
            <div class="card">
                <div class="card-body">
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
                                    <th width="5%">#</th>
                                    <th width="14%">Aksi</th>
                                    <th width="10%">Kode Lapak</th>
                                    <th width="15%">Nama Lapak</th>
                                    <th width="15%">Cabang</th>
                                    {{-- <th width="15%">Alamat</th>
                                    <th width="8%">Kota</th> --}}
                                    <th width="8%">Status</th>
                                    <th width="10%">Approval</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lapaks as $index => $lapak)
                                    <tr>
                                        <td>{{ $lapaks->firstItem() + $index }}</td>
                                        <td>
                                            <div class="btn-group mb-2" role="group">
                                                <a href="{{ route('petugas.lapak.show', $lapak->id) }}"
                                                    class="btn btn-sm btn-info" title="Detail">
                                                    <i class="bi bi-eye-fill"></i>
                                                </a>

                                                {{-- <a href="{{ route('petugas.lapak.edit', $lapak->id) }}"
                                                   class="btn btn-sm btn-warning"
                                                   title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <form action="{{ route('petugas.lapak.destroy', $lapak->id) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus lapak ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form> --}}
                                            </div>
                                            <a href="{{ route('petugas.lapak.kirim-sampah', $lapak->id) }}"
                                                class="btn btn-sm btn-primary" style="margin-bottom: 4px;">
                                                <i class="bi bi-truck"></i>
                                                {{-- Kirim Sampah --}}
                                            </a>
                                            <a href="{{ route('petugas.lapak.setor-sampah', $lapak->id) }}"
                                                class="btn btn-sm btn-success">
                                                <i class="bi bi-plus-circle"></i>
                                                {{-- Setor Sampah --}}
                                            </a>
                                        </td>
                                        <td><strong>{{ $lapak->kode_lapak }}</strong></td>
                                        <td>{{ $lapak->nama_lapak }}</td>
                                        <td>
                                            {{ $lapak->cabang->nama_cabang ?? '-' }}<br>
                                            <small class="text-muted">{{ $lapak->cabang->kode_cabang ?? '-' }}</small>
                                        </td>
                                        {{-- <td>{{ Str::limit($lapak->alamat, 30) }}</td>
                                        <td>{{ $lapak->kota ?? '-' }}</td> --}}
                                        <td>
                                            @if ($lapak->status == 'aktif')
                                                <span class="badge badge-success"
                                                    style="color: #000 !important;">Aktif</span>
                                            @else
                                                <span class="badge badge-danger" style="color: #000 !important;">Tidak
                                                    Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($lapak->approval_status == 'pending')
                                                <span class="badge badge-warning"
                                                    style="color: #000 !important;">Pending</span>
                                            @elseif($lapak->approval_status == 'approved')
                                                <span class="badge badge-success"
                                                    style="color: #000 !important;">Approved</span>
                                            @else
                                                <span class="badge badge-danger"
                                                    style="color: #000 !important;">Rejected</span>
                                                @if ($lapak->rejection_reason)
                                                    <br><small class="text-danger"
                                                        title="{{ $lapak->rejection_reason }}">{{ Str::limit($lapak->rejection_reason, 20) }}</small>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            <p class="text-muted my-3">Belum ada data lapak</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
                        <div class="text-muted small mb-2 mb-md-0">
                            Menampilkan {{ $lapaks->firstItem() ?? 0 }} - {{ $lapaks->lastItem() ?? 0 }}
                            dari {{ $lapaks->total() }} data
                        </div>
                        <div>
                            {{ $lapaks->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('sweetalert::alert')
@endpush

@extends('layouts.template')

@section('title', 'Daftar Gudang')

@section('style')
    <style>
        /* Semua teks tabel jadi hitam */
        table.table,
        table.table th,
        table.table td,
        table.table td * { /* menarget semua elemen di dalam td */
            color: #000 !important;
        }

        /* Jika ada link di dalam tabel */
        table.table a {
            color: #000 !important;
            text-decoration: none;
        }

        /* Badge tetap putih teks, tapi background tidak berubah */
        table.table td span.badge {
            color: #111010 !important;
        }
    </style>
@endsection


@section('main')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 class="fw-bold">Daftar Gudang</h3>
        <a href="{{ route('admin.gudangs.create') }}" class="btn btn-primary">Tambah Gudang</a>
    </div>

   <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Kode Gudang</th>
                            <th>Nama Gudang</th>
                            <th>Alamat</th>
                            <th>Kota</th>
                            <th>Provinsi</th>
                            <th>Kode Pos</th>
                            <th>Telepon</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gudangs as $gudang)
                            <tr>
                                <td>{{ $gudang->kode_gudang }}</td>
                                <td>{{ $gudang->nama_gudang }}</td>
                                <td>{{ $gudang->alamat }}</td>
                                <td>{{ $gudang->kota }}</td>
                                <td>{{ $gudang->provinsi }}</td>
                                <td>{{ $gudang->kode_pos }}</td>
                                <td>{{ $gudang->telepon }}</td>
                                <td>{{ ucfirst($gudang->status) }}</td>
                                <td>
                                    <a href="{{ route('admin.gudangs.edit', $gudang->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.gudangs.destroy', $gudang->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus gudang ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Belum ada data gudang</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>

                <!-- Pagination jika pakai paginate -->
                {{-- {{ $labels->links() }} --}}
            </div>
        </div>
    </div>
</div>
@endsection

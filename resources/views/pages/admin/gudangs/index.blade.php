@extends('layouts.template')

@section('title', 'Daftar Customer')

@push('style')
    <style>
        table.table td,
        table.table th {
            color: #000 !important;
        }
        table.table thead th {
            color: #000 !important;
        }
        table.table td span {
            color: #fff !important;
        }
    </style>
@endpush

@section('main')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">List Customer</h3>
        <h6 class="op-7 mb-2">Anda dapat mengelola semua customer, seperti menambah, mengedit, menghapus, dan lainnya.</h6>
    </div>
    <div class="ms-md-auto py-2 py-md-0">
        <div class="section-header-button d-flex gap-2">
            <a href="{{ route('admin.gudangs.create') }}" class="btn btn-primary btn-round">Tambah Customer</a>
            <!-- Tombol download template Excel via JS -->
            <button id="download-template" class="btn btn-info btn-round">Download Template Excel</button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="mb-3 mt-2">
                    <form action="{{ route('admin.gudangs.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group">
                            <input type="file" name="file" class="form-control" accept=".xlsx, .xls" required>
                            <button class="btn btn-success" type="submit">Import Excel</button>
                        </div>
                    </form>
                </div>

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
                                <th>Kode Customer</th>
                                <th>Cabang</th>
                                <th>Nama Customer</th>
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
                                    <td>{{ $gudang->cabang ? ($gudang->cabang->nama_cabang ?? $gudang->cabang->id) : '-' }}</td>
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
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Load SheetJS dari CDN -->
<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
<script>
    document.getElementById('download-template').addEventListener('click', function() {
        // Data header Excel
        const ws_data = [
            ["kode_gudang", "nama_gudang", "alamat", "kota", "provinsi", "kode_pos", "telepon", "status"],
            ["GD001", "Gudang Utama", "Jl. Merdeka No.1", "Jakarta", "DKI Jakarta", "10110", "021-1234567", "aktif"],
            ["GD002", "Gudang Cabang", "Jl. Sudirman No.10", "Bandung", "Jawa Barat", "40115", "022-7654321", "aktif"]
        ];

        // Buat workbook dan worksheet
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.aoa_to_sheet(ws_data);
        XLSX.utils.book_append_sheet(wb, ws, "Template Gudang");

        // Download file
        XLSX.writeFile(wb, "template_gudangs.xlsx");
    });
</script>
@endpush

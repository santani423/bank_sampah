@extends('layouts.template')

@section('title', 'Daftar Label')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">List Label</h3>
        <h6 class="op-7 mb-2">Anda dapat mengelola semua label, seperti menambah, mengedit, menghapus, dan lainnya.</h6>
    </div>
    <div class="ms-md-auto py-2 py-md-0">
        <div class="section-header-button">
            <a href="{{ route('admin.labels.create') }}" class="btn btn-primary btn-round">Tambah Label</a>
        </div>
    </div>
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
                    <table class="table table-hover table-bordered table-head-bg-primary">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Nama Label</th>
                                <th>Slug</th>
                                <th>Warna</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($labels as $index => $label)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $label->name }}</td>
                                    <td>{{ $label->slug }}</td>
                                    <td>
                                        @if($label->color)
                                            <span style="background: {{ $label->color }}; padding:4px 12px; border-radius:8px; color:#fff;">
                                                {{ $label->color }}
                                            </span>
                                        @else
                                            <em>-</em>
                                        @endif
                                    </td>
                                    <td>{{ $label->description ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('admin.labels.edit', $label->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('admin.labels.destroy', $label->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Yakin ingin menghapus label ini?')" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada data label</td>
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

@push('scripts')
    <!-- JS Libraries -->

    <!-- Page Specific JS File -->
@endpush

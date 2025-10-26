@extends('layouts.template')

@section('title', 'Daftar Activity')

@push('style')
    <style>
        /* Semua teks tabel jadi hitam */
        table.table td,
        table.table th {
            color: #000 !important;
        }

        /* Header tabel juga hitam */
        table.table thead th {
            color: #000 !important;
        }

        /* Badge warna tetap dengan teks putih */
        table.table td span {
            color: #fff !important;
        }
    </style>
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">List Activity</h3>
            <h6 class="op-7 mb-2">Anda dapat mengelola semua activity, seperti menambah, mengedit, menghapus, dan lainnya.
            </h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <div class="section-header-button">
                <a href="{{ route('admin.activities.create') }}" class="btn btn-primary btn-round">Tambah Activity</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    {{-- <th>Slug</th> --}}
                                    {{-- <th>Deskripsi</th> --}}
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    {{-- <th>Location</th> --}}
                                    <th>Label</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activities as $index => $activity)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $activity->title }}</td>
                                        {{-- <td>{{ $activity->slug }}</td> --}}
                                        {{-- <td>{{ $activity->description ?? '-' }}</td> --}}
                                        <td>{{ $activity->start_date ?? '-' }}</td>
                                        <td>{{ $activity->end_date ?? '-' }}</td>
                                        {{-- <td>{{ $activity->location ?? '-' }}</td> --}}
                                        <td>{{ $activity->label->name ?? '-' }}</td>
                                        <td>{{ ucfirst($activity->status) }}</td>
                                        <td>
                                            <a href="{{ route('admin.activities.show', $activity->id) }}"
                                                class="btn btn-sm btn-info">View</a>
                                            <a href="{{ route('admin.activities.edit', $activity->id) }}"
                                                class="btn btn-sm btn-warning">Edit</a>
                                            <form action="{{ route('admin.activities.destroy', $activity->id) }}"
                                                method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('Yakin ingin menghapus activity ini?')"
                                                    class="btn btn-sm btn-danger">Hapus</button>
                                            </form>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">Belum ada data activity</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination jika pakai paginate -->
                    {{-- {{ $activities->links() }} --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->

    <!-- Page Specific JS File -->
@endpush

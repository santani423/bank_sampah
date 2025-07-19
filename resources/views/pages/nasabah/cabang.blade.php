@extends('layouts.template')

@section('title', 'Dashboard Nasabah')

@push('style')
    <!-- Tambahan CSS jika diperlukan -->
@endpush

@section('main')
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <h4>Daftar Cabang</h4>
            <!-- Tombol Tambah Cabang -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCabang">
                Tambah Cabang
            </button>
        </div>

        <!-- TABEL UTAMA -->
        <div class="table-responsive">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <table class="table table-hover table-bordered table-head-bg-primary">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Cabang</th>
                        <th>Alamat</th>
                        <th>Kota</th>
                        <th>Provinsi</th>
                        <th>Telepon</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($userCabang as $cabang)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $cabang->nama_cabang }}</td>
                            <td>{{ $cabang->alamat }}</td>
                            <td>{{ $cabang->kota }}</td>
                            <td>{{ $cabang->provinsi }}</td>
                            <td>{{ $cabang->telepon }}</td>
                             
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL TAMBAH CABANG -->
    <div class="modal fade" id="modalCabang" tabindex="-1" aria-labelledby="modalCabangLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl"> <!-- modal-xl agar lebih lebar -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCabangLabel">Tambah Cabang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <!-- TABEL DI MODAL -->
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-head-bg-primary">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Cabang</th>
                                    <th>Alamat</th>
                                    <th>Kota</th>
                                    <th>Provinsi</th>
                                    <th>Telepon</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cabangList as $cabang)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $cabang->nama_cabang }}</td>
                                        <td>{{ $cabang->alamat }}</td>
                                        <td>{{ $cabang->kota }}</td>
                                        <td>{{ $cabang->provinsi }}</td>
                                        <td>{{ $cabang->telepon }}</td>
                                        <td>
                                            <form action="{{ route('nasabah.cabang.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $cabang->id }}">
                                                <button type="submit" class="btn btn-sm btn-primary">Join</button>
                                            </form>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Tambahkan JS tambahan jika diperlukan --}}
@endpush

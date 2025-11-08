@extends('layouts.template')

@section('title', 'Nasabah')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Rekanan</h3>
            {{-- <h6 class="op-7 mb-2">Anda dapat mengelola semua nasabah, seperti mengedit, menghapus, dan lainnya.</h6> --}}
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <div class="section-header-button">
                {{-- <a href="{{ route('petugas.nasabah.create') }}" class="btn btn-primary btn-round">Tambah Nasabah Baru</a> --}}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-5">
                <div class="card-body">

                    <div class="float-right">
                        <form method="GET" action="{{ route('petugas.rekanan.index') }}">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Cari Nama" name="nama_nasabah"
                                    value="{{ request('nama_nasabah') }}">
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

                    <div class="clearfix mb-3"></div>
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
                                    <th>#</th>
                                    <th style="width: 250px">Aksi</th>
                                    <th>Nama</th>
                                    <th>No. Registrasi</th>
                                    <th>No. HP</th> 
                                    <th>Cabang</th> 
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>
                        </table>

                    </div>
                    <div class="float-right">
                         
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

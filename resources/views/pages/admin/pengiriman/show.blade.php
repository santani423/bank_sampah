@extends('layouts.template')

@section('title', 'Detail Pengiriman Sampah')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">Detail Pengiriman Sampah</h3>
        <h6 class="op-7 mb-2">Informasi lengkap mengenai pengiriman yang dilakukan.</h6>
    </div>
    <div class="ms-md-auto py-2 py-md-0">
        <a href="{{ route('petugas.pengiriman.index') }}" class="btn btn-secondary btn-round">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left me-1" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 1-.5.5H2.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.498.498 0 0 1-.146-.354v-.004a.498.498 0 0 1 .146-.354l4-4a.5.5 0 1 1 .708.708L2.707 7.5H14.5A.5.5 0 0 1 15 8z"/>
            </svg>
            Kembali
        </a>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white fw-bold">
                Informasi Pengiriman
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th style="width: 40%">Kode Pengiriman</th>
                        <td>{{ $pengiriman->kode_pengiriman }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Pengiriman</th>
                        <td>{{ \Carbon\Carbon::parse($pengiriman->tanggal_pengiriman)->format('d-m-Y') }}</td>
                    </tr>
                    <tr>
                        <th>Cabang</th>
                        <td>{{ $pengiriman->cabang->nama_cabang ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Gudang</th>
                        <td>{{ $pengiriman->gudang->nama_gudang ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @switch($pengiriman->status_pengiriman)
                                @case('draft')
                                    <span class="badge bg-secondary">Draft</span>
                                    @break
                                @case('dikirim')
                                    <span class="badge bg-info text-white">Dikirim</span>
                                    @break
                                @case('diterima')
                                    <span class="badge bg-success">Diterima</span>
                                    @break
                                @case('batal')
                                    <span class="badge bg-danger">Batal</span>
                                    @break
                                @default
                                    <span class="badge bg-light text-dark">Tidak Diketahui</span>
                            @endswitch
                        </td>
                    </tr>
                    <tr>
                        <th>Dibuat Pada</th>
                        <td>{{ \Carbon\Carbon::parse($pengiriman->created_at)->format('d-m-Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white fw-bold">
                Detail Sampah Dikirim
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th style="width: 50px;">No</th>
                                <th>Jenis Sampah</th>
                                <th>Berat (Kg)</th> 
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pengiriman->detailPengiriman as $index => $detail)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $detail->sampah->nama_sampah ?? '-' }}</td>
                                    <td class="text-end">{{ number_format($detail->berat_kg, 2) }}</td>
                                     
                                    <td>{{ $detail->catatan ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        Tidak ada detail pengiriman sampah.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 text-end">
                    <a href="{{ route('petugas.pengiriman.index') }}" class="btn btn-outline-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left me-1" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 1-.5.5H2.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.498.498 0 0 1-.146-.354v-.004a.498.498 0 0 1 .146-.354l4-4a.5.5 0 1 1 .708.708L2.707 7.5H14.5A.5.5 0 0 1 15 8z"/>
                        </svg>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
@endpush

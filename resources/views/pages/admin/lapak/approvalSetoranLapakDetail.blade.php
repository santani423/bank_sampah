@extends('layouts.template')

@section('title', 'Detail Transaksi Lapak')

@section('main')
<div class="container py-4">
    <div class="row mb-3">
        <div class="col-12">
            <h3 class="fw-bold">Detail Transaksi Lapak</h3>
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm mb-2"><i class="bi bi-arrow-left"></i> Kembali</a>
            <a href="{{ route('admin.lapak.transaksi.download', $transaksi->id) }}" class="btn btn-success btn-sm mb-2"><i class="bi bi-download"></i> Download Detail</a>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-6">
                    <div><strong>Kode Transaksi:</strong> {{ $transaksi->kode_transaksi }}</div>
                    <div><strong>Tanggal:</strong> {{ $transaksi->tanggal_transaksi }}</div>
                    <div><strong>Status:</strong> <span class="badge badge-warning" style="color:#000 !important;">{{ $transaksi->status }}</span></div>
                </div>
                <div class="col-md-6">
                    <div><strong>Jumlah Total:</strong> Rp {{ number_format($transaksi->total_transaksi,0,',','.') }}</div>
                    <div><strong>Petugas:</strong> {{ $transaksi->petugas->nama ?? '-' }}</div>
                </div>
            </div>
            <hr>
            <h5>Detail Item Transaksi</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Sampah</th>
                            <th>Berat (kg)</th>
                            <th>Harga per Kg</th>
                            <th>Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi->detail_transaksi as $idx => $detail)
                        <tr>
                            <td>{{ $idx+1 }}</td>
                            <td>{{ $detail->sampah->nama_sampah ?? '-' }}</td>
                            <td>{{ $detail->berat_kg }}</td>
                            <td>Rp {{ number_format($detail->harga_per_kg,0,',','.') }}</td>
                            <td>Rp {{ number_format($detail->total_harga,0,',','.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

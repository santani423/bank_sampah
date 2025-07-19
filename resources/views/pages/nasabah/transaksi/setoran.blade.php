@extends('layouts.template')

@section('title', 'Setoran Nasabah')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
 <!-- Riwayat Transaksi Setoran -->
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Riwayat Transaksi Setoran</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-head-bg-primary">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>Detail Sampah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($riwayatSetoran as $index => $transaksi)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $transaksi->kode_transaksi }}</td>
                                        <td>{{ $transaksi->tanggal_transaksi }}</td>
                                        <td>
                                            <ul>
                                                @foreach ($transaksi->detailTransaksi as $detail)
                                                    <li>{{ $detail->sampah->nama_sampah }} - {{ $detail->berat_kg }} kg
                                                        (Rp{{ number_format($detail->harga_total, 2) }})
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>
                                            <a href="{{ route('nasabah.transaksi.print', $transaksi->id) }}" class="btn btn-primary btn-sm">Cetak Nota</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">
                                            <div class="text-center">Belum ada Transaksi.</div>
                                        </td>
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
    <!-- JS Libraries -->

    <!-- Page Specific JS File -->
@endpush

@extends('layouts.template')

@section('title', 'Setoran Nasabah')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <!-- Riwayat Penarikan Saldo -->
    <div class="row mt-4">
        <div class="col-lg-12">

            <!-- Tombol Ajukan Penarikan -->
            <div class="mb-3 text-end">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalPenarikan">
                    Ajukan Penarikan
                </button>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">Riwayat Penarikan Saldo</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-head-bg-primary">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($riwayatPenarikan as $index => $penarikan)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $penarikan->tanggal_pengajuan }}</td>
                                        <td>Rp{{ number_format($penarikan->jumlah_pencairan, 2) }}</td>
                                        <td>{{ ucfirst($penarikan->status) }}</td>
                                        <td>{{ $penarikan->keterangan ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="text-center">Belum ada Transaksi Penarikan.</div>
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

    <!-- Modal Penarikan -->
    <div class="modal fade" id="modalPenarikan" tabindex="-1" aria-labelledby="modalPenarikanLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('nasabah.transaksi.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPenarikanLabel">Ajukan Penarikan Dana</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="jumlah_pencairan" class="form-label">Jumlah Penarikan</label>
                        <input type="number" name="jumlah_pencairan" id="jumlah_pencairan" class="form-control" required min="1000" step="1000">
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="3" placeholder="Contoh: Penarikan ke rekening pribadi"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ajukan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <!-- Pastikan Bootstrap JS dimuat -->
@endpush

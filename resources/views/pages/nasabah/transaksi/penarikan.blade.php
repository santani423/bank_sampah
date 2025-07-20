@extends('layouts.template')

@section('title', 'Metode Penarikan Nasabah')

@push('style')
@endpush

@section('main')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">Dashboard</h3>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Metode Penarikan</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPenarikan">+ Tambah Penarikan</button>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Riwayat Penarikan</h4>
            </div>
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
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-head-bg-primary">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Jumlah Penarikan</th>
                                <th>Metode Penarikan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($riwayatPenarikan as $index => $penarikan)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $penarikan->created_at->format('d-m-Y H:i') }}</td>
                                    <td>Rp {{ number_format($penarikan->jumlah_pencairan, 0, ',', '.') }}</td>
                                    <td>{{ $penarikan->metode->nama_metode_pencairan ?? 'N/A' }}</td>
                                    <td>
                                        @if($penarikan->status == 'pending')
                                            <span class="badge text-bg-info">Menunggu</span>
                                        @elseif($penarikan->status == 'disetujui')
                                            <span class="badge text-bg-success">Berhasil</span>
                                        @elseif($penarikan->status == 'failed')
                                            <span class="badge text-bg-danger">Gagal</span>
                                        @else
                                            <span class="badge text-bg-secondary">Gagal</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if ($riwayatPenarikan->isEmpty())
                        <div class="text-center mt-3">Belum ada riwayat penarikan.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Penarikan -->
<div class="modal fade" id="modalPenarikan" tabindex="-1" aria-labelledby="modalPenarikanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('nasabah.transaksi.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPenarikanLabel">Tambah Metode Penarikan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    {{-- Tombol pilihan jumlah --}}
                    <div class="mb-3">
                        <label class="form-label d-block">Pilih Jumlah Penarikan</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ([10000, 20000, 50000, 100000, 150000] as $jumlah)
                                <button type="button" class="btn btn-outline-primary btn-primary btn-jumlah" data-jumlah="{{ $jumlah }}">
                                    {{ number_format($jumlah, 0, ',', '.') }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Input manual --}}
                    <div class="mb-3">
                        <label for="manual_jumlah" class="form-label">Atau masukkan jumlah secara manual</label>
                        <input type="number" name="jumlah" class="form-control" id="manual_jumlah"
                            placeholder="Masukkan jumlah manual (contoh: 75000)" autocomplete="off">
                    </div>

                    {{-- Input hidden --}}
                    <input type="hidden" name="jumlah_pencairan" id="jumlah_pencairan" required>

                    {{-- Pilihan metode --}}
                    <div class="mb-3 mt-3">
                        <label for="metode" class="form-label">Metode</label>
                        <select name="metode_pencairan_id" id="metode" class="form-select" required>
                            <option value="">Pilih Metode Penarikan</option>
                            @foreach ($metodePenarikan as $metode)
                                <option value="{{ $metode->id }}">{{ $metode->jenisMetodePenarikan->nama }} -
                                    {{ $metode->nama_metode_pencairan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Kirim Penarikan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const buttons = document.querySelectorAll('.btn-jumlah');
        const inputJumlahHidden = document.getElementById('jumlah_pencairan');
        const manualInput = document.getElementById('manual_jumlah');

        // Saat tombol jumlah diklik
        buttons.forEach(button => {
            button.addEventListener('click', () => {
                const selectedValue = button.dataset.jumlah;

                // Reset semua tombol
                // buttons.forEach(btn => {
                //     btn.classList.remove('active', 'btn-primary');
                //     btn.classList.add('btn-outline-primary');
                // });

                // Aktifkan tombol terpilih
                // button.classList.remove('btn-outline-primary');
                // button.classList.add('btn-primary', 'active');

                // Set hidden input dan input manual
                inputJumlahHidden.value = selectedValue;
                manualInput.value = selectedValue;
            });
        });

        // Saat input manual berubah
        manualInput.addEventListener('input', function () {
            // Reset tombol
            buttons.forEach(btn => {
                btn.classList.remove('active', 'btn-primary');
                btn.classList.add('btn-outline-primary');
            });

            // Set hidden input dari manual
            inputJumlahHidden.value = this.value;
        });
    });
</script>
@endsection

@push('scripts')
@endpush

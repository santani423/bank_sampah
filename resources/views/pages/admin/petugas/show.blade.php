@extends('layouts.template')

@section('title', 'Detail Petugas')

@push('style')
    <!-- CSS Libraries -->
    <style>
        /* Semua teks tabel jadi hitam */
        table.table,
        table.table th,
        table.table td,
        table.table td * {
            color: #000 !important;
        }

        /* Jika ada link di dalam tabel */
        table.table a {
            color: #000 !important;
            text-decoration: none;
        }

        /* Badge tetap dengan warna yang sesuai tapi teks hitam */
        table.table td span.badge {
            color: #fff !important;
        }
    </style>
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Detail Petugas</h3>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <a href="{{ route('admin.petugas.index') }}" class="btn btn-secondary btn-round">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('admin.petugas.edit', $petugas->id) }}" class="btn btn-primary btn-round">
                <i class="fas fa-pencil-alt"></i> Edit Petugas
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Informasi Petugas</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama</label>
                        <p class="form-control-plaintext">{{ $petugas->nama }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <p class="form-control-plaintext">{{ $petugas->email }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Username</label>
                        <p class="form-control-plaintext">{{ $petugas->username }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Role</label>
                        <p class="form-control-plaintext">
                            <span class="badge badge-{{ $petugas->role == 'admin' ? 'primary' : 'info' }}">
                                {{ ucfirst($petugas->role) }}
                            </span>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Saldo Petugas</label>
                        <p class="form-control-plaintext">
                            <span class="badge badge-success fs-6">
                                Rp {{ number_format($petugas->saldoPetugas->saldo ?? 0, 0, ',', '.') }}
                            </span>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Terdaftar Sejak</label>
                        <p class="form-control-plaintext">
                            {{ $petugas->created_at ? $petugas->created_at->format('d M Y H:i') : '-' }}
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Terakhir Diperbarui</label>
                        <p class="form-control-plaintext">
                            {{ $petugas->updated_at ? $petugas->updated_at->format('d M Y H:i') : '-' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Form Alokasi Saldo --}}
            @if($adminPetugas->role == 'admin')
            <div class="card mt-3">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0 text-white">Alokasi Saldo ke Petugas</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-bold">Saldo Admin Saat Ini</label>
                        <p class="form-control-plaintext">
                            <span class="badge badge-{{ ($adminSaldo->saldo ?? 0) >= 1000000 ? 'success' : 'danger' }} fs-6">
                                Rp {{ number_format($adminSaldo->saldo ?? 0, 0, ',', '.') }}
                            </span>
                        </p>
                        @if(($adminSaldo->saldo ?? 0) < 1000000)
                            <small class="text-danger">
                                <i class="fas fa-exclamation-triangle"></i> Saldo admin kurang dari Rp 1.000.000. Tidak dapat melakukan alokasi.
                            </small>
                        @endif
                    </div>

                    <form action="{{ route('admin.petugas.alokasiSaldo', $petugas->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nominal" class="form-label">Nominal Alokasi <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('nominal') is-invalid @enderror" 
                                   id="nominal" 
                                   name="nominal" 
                                   placeholder="Masukkan nominal" 
                                   required
                                   min="1"
                                   {{ ($adminSaldo->saldo ?? 0) < 1000000 ? 'disabled' : '' }}>
                            @error('nominal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                      id="keterangan" 
                                      name="keterangan" 
                                      rows="3" 
                                      placeholder="Keterangan alokasi (opsional)"
                                      {{ ($adminSaldo->saldo ?? 0) < 1000000 ? 'disabled' : '' }}></textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" 
                                class="btn btn-primary w-100" 
                                {{ ($adminSaldo->saldo ?? 0) < 1000000 ? 'disabled' : '' }}>
                            <i class="fas fa-money-bill-wave"></i> Alokasikan Saldo
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Riwayat Transaksi</h4>
                </div>
                <div class="card-body">
                    @if($petugas->transaksi && $petugas->transaksi->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tanggal</th>
                                        <th>Kode Transaksi</th>
                                        <th>Nasabah</th>
                                        <th>Total Berat</th>
                                        <th>Total Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($petugas->transaksi as $index => $transaksi)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $transaksi->tanggal_transaksi ? \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y') : '-' }}</td>
                                            <td>{{ $transaksi->kode_transaksi ?? '-' }}</td>
                                            <td>{{ $transaksi->nasabah->nama ?? '-' }}</td>
                                            <td>{{ number_format($transaksi->total_berat ?? 0, 2) }} kg</td>
                                            <td>Rp {{ number_format($transaksi->total_harga ?? 0, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold">
                                        <td colspan="4" class="text-end">Total:</td>
                                        <td>{{ number_format($petugas->transaksi->sum('total_berat') ?? 0, 2) }} kg</td>
                                        <td>Rp {{ number_format($petugas->transaksi->sum('total_harga') ?? 0, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Belum ada riwayat transaksi untuk petugas ini.
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="card-title">Statistik</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card card-stats card-round">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="icon-big text-center">
                                                <i class="fas fa-file-invoice text-warning"></i>
                                            </div>
                                        </div>
                                        <div class="col-7 col-stats">
                                            <div class="numbers">
                                                <p class="card-category">Total Transaksi</p>
                                                <h4 class="card-title">{{ $petugas->transaksi ? $petugas->transaksi->count() : 0 }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card card-stats card-round">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="icon-big text-center">
                                                <i class="fas fa-weight text-success"></i>
                                            </div>
                                        </div>
                                        <div class="col-7 col-stats">
                                            <div class="numbers">
                                                <p class="card-category">Total Berat</p>
                                                <h4 class="card-title">{{ number_format($petugas->transaksi ? $petugas->transaksi->sum('total_berat') : 0, 2) }} kg</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card card-stats card-round">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="icon-big text-center">
                                                <i class="fas fa-money-bill-wave text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="col-7 col-stats">
                                            <div class="numbers">
                                                <p class="card-category">Total Nilai</p>
                                                <h4 class="card-title">Rp {{ number_format($petugas->transaksi ? $petugas->transaksi->sum('total_harga') : 0, 0, ',', '.') }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- History Alokasi Saldo --}}
            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="card-title">History Alokasi Saldo</h4>
                </div>
                <div class="card-body">
                    @if($petugas->alokasiDiterima && $petugas->alokasiDiterima->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tanggal</th>
                                        <th>Dari Admin</th>
                                        <th>Nominal</th>
                                        <th>Saldo Sebelum</th>
                                        <th>Saldo Sesudah</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($petugas->alokasiDiterima->sortByDesc('created_at') as $index => $alokasi)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $alokasi->created_at->format('d M Y H:i') }}</td>
                                            <td>{{ $alokasi->admin->nama ?? '-' }}</td>
                                            <td>
                                                <span  >
                                                    + Rp {{ number_format($alokasi->nominal, 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td>Rp {{ number_format($alokasi->saldo_petugas_sebelum, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($alokasi->saldo_petugas_sesudah, 0, ',', '.') }}</td>
                                            <td>{{ $alokasi->keterangan ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold">
                                        <td colspan="3" class="text-end">Total Alokasi Diterima:</td>
                                        <td colspan="4">
                                            <span class=" fs-6">
                                                Rp {{ number_format($petugas->alokasiDiterima->sum('nominal'), 0, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Belum ada history alokasi saldo untuk petugas ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
@endpush

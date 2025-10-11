@extends('layouts.template')

@section('title', 'Pengajuan Tarik Saldo')

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Permintaan Penarikan Saldo</h3>
            <h6 class="op-7 mb-2">Anda dapat mengelola permintaan penarikan saldo yang masuk.</h6>
        </div>
    </div>

    {{-- ✅ Alert hasil proses --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle text-center">
                            <thead class="table-primary">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Nama Nasabah</th>
                                    <th>Jumlah Penarikan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pencairanSaldo as $index => $pencairan)
                                    <tr>
                                        <td>{{ $pencairanSaldo->firstItem() + $index }}</td>
                                        <td>{{ $pencairan->created_at->format('d-m-Y H:i') }}</td>
                                        <td>{{ $pencairan->nasabah->nama_lengkap ?? '-' }}</td>
                                        <td>Rp {{ number_format($pencairan->jumlah_pencairan, 0, ',', '.') }}</td>
                                        <td>
                                            {{-- Tombol Setujui --}}
                                            <form action="{{ route('admin.tarik-saldo.setujui', $pencairan->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="jumlah_pencairan" value="{{ $pencairan->jumlah_pencairan }}">
                                                <button type="submit" class="btn btn-success btn-sm"
                                                    onclick="return confirm('Apakah Anda yakin ingin menyetujui pengajuan ini?')">
                                                    Setujui
                                                </button>
                                            </form>

                                            {{-- Tombol Tolak (buka modal) --}}
                                            <button type="button" class="btn btn-danger btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalTolak"
                                                onclick="setRejectData({{ $pencairan->id }}, '{{ number_format($pencairan->jumlah_pencairan, 0, ',', '.') }}', '{{ $pencairan->nasabah->nama_lengkap }}')">
                                                Tolak
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Tidak ada data pengajuan penarikan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="float-end mt-3">
                        {{ $pencairanSaldo->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ Modal Tolak -->
    <div class="modal fade" id="modalTolak" tabindex="-1" aria-labelledby="modalTolakLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formTolak" method="POST">
                    @csrf
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="modalTolakLabel">Tolak Pengajuan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="id" id="tolakId">

                        <div class="mb-3">
                            <strong>Nama Nasabah:</strong> <span id="namaNasabah" class="text-primary"></span><br>
                            <strong>Jumlah Penarikan:</strong> Rp <span id="jumlahPenarikan" class="text-success"></span>
                        </div>

                        <div class="form-group">
                            <label for="keterangan" class="fw-semibold">Keterangan Penolakan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required placeholder="Tulis alasan penolakan di sini..."></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ✅ Script -->
    <script>
        function setRejectData(id, jumlah, nama) {
            const url = "{{ route('admin.tarik-saldo.tolak', ':id') }}".replace(':id', id);
            document.getElementById('formTolak').action = url;
            document.getElementById('tolakId').value = id;
            document.getElementById('jumlahPenarikan').textContent = jumlah;
            document.getElementById('namaNasabah').textContent = nama;
        }
    </script>
@endsection

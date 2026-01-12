@extends('layouts.template')

@section('title', 'Metode Penarikan Nasabah')

@section('main')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">Dashboard</h3>
    </div>
</div>

<div class="card mb-5">
    <div class="card-body">
        <h5 class="card-title">Metode Penarikan</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPenarikan">
            + Tambah Penarikan
        </button>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Riwayat Penarikan</h4>
            </div>

            {{-- Alert messages --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Tabel Riwayat --}}
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
                                            <span class="badge bg-info">Menunggu</span>
                                        @elseif($penarikan->status == 'disetujui')
                                            <span class="badge bg-success">Berhasil</span>
                                        @elseif($penarikan->status == 'failed')
                                            <span class="badge bg-danger">Gagal</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Diketahui</span>
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
        <form id="formPenarikan" action="{{ route('api.nasabah.requestWithdrawal') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPenarikanLabel">Tambah Metode Penarikan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- Tombol pilihan jumlah --}}
                    <div class="mb-3">
                        <label class="form-label d-block">Pilih Jumlah Penarikan</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ([10000, 20000, 50000, 100000, 150000] as $jumlah)
                                <button type="button" class="btn btn-primary btn-jumlah" data-jumlah="{{ $jumlah }}">
                                    Rp {{ number_format($jumlah, 0, ',', '.') }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Input manual dengan format Rupiah --}}
                    <div class="mb-3">
                        <label for="manual_jumlah" class="form-label">Atau masukkan jumlah secara manual</label>
                        <input type="text" class="form-control" id="manual_jumlah"
                            placeholder="Masukkan jumlah manual (contoh: 75.000)" autocomplete="off">
                        <input type="hidden" name="jumlah_pencairan" id="jumlah_pencairan" required>
                        <small class="text-muted">
                            Minimal penarikan: <strong>Rp {{ number_format($minPenarikan, 0, ',', '.') }}</strong>
                        </small>
                    </div>

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
                    <button type="submit" class="btn btn-primary w-100">Kirim Penarikan</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Validasi & Format Rupiah --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.btn-jumlah');
    const inputHidden = document.getElementById('jumlah_pencairan');
    const inputManual = document.getElementById('manual_jumlah');
    const form = document.getElementById('formPenarikan');
    const minPenarikan = {{ $minPenarikan ?? 0 }};

    // Fungsi format Rupiah
    function formatRupiah(angka) {
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Saat klik tombol preset jumlah
    buttons.forEach(button => {
        button.addEventListener('click', () => {
            const val = parseInt(button.dataset.jumlah);
            inputHidden.value = val;
            inputManual.value = formatRupiah(val);
        });
    });

    // Saat input manual diketik
    inputManual.addEventListener('input', function () {
        let val = this.value.replace(/[^\d]/g, ''); // hapus semua non-digit
        if (val) {
            this.value = formatRupiah(val);
            inputHidden.value = val;
        } else {
            this.value = '';
            inputHidden.value = '';
        }
    });

    // Submit form pakai AJAX
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const jumlah = parseFloat(inputHidden.value || 0);
        if (jumlah < minPenarikan) {
            alert(`Jumlah penarikan minimal adalah Rp ${minPenarikan.toLocaleString('id-ID')}`);
            return;
        }

        // Ambil data form
        const formData = new FormData(form);

        // AJAX pakai jQuery
        $.ajax({
            url: form.action,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            success: function(res) {
                // Tampilkan notifikasi sukses
                alert('Penarikan berhasil diajukan!');
                // Tutup modal
                var modal = bootstrap.Modal.getInstance(document.getElementById('modalPenarikan'));
                if (modal) modal.hide();
                // Reset form
                form.reset();
                inputManual.value = '';
                inputHidden.value = '';
                // Optional: reload tabel riwayat (bisa pakai AJAX atau location.reload())
                location.reload();
            },
            error: function(xhr) {
                let msg = 'Terjadi kesalahan. Mohon coba lagi.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                alert(msg);
            }
        });
    });
});
</script>
@endsection

@extends('layouts.template')

@section('title', 'Detail Transaksi Lapak')

@section('main')
    <div class="container py-4">
        <div class="row mb-3">
            <div class="col-12">
                <h3 class="fw-bold">Detail Transaksi Lapak</h3>
                <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm mb-2"><i class="bi bi-arrow-left"></i>
                    Kembali</a>
                <a href="{{ route('admin.lapak.transaksi.download', $transaksi->id) }}" class="btn btn-success btn-sm mb-2"><i
                        class="bi bi-download"></i> Download Detail</a>
                <!-- Tombol Pencairan Data -->
                <!-- Loading Spinner -->
                <div id="loadingApi"
                    style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(255,255,255,0.6);z-index:9999;align-items:center;justify-content:center;">
                    <div class="spinner-border text-primary" role="status" style="width:4rem;height:4rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                @if ($transaksi->approval === 'pending')
                    <fieldset class="mb-2">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#modalTransfer">
                            <i class="bi bi-bank"></i> Transfer
                        </button>
                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                            data-bs-target="#modalAmbilSaldo">
                            <i class="bi bi-wallet2"></i> Ambil dari Saldo
                        </button>
                        <!-- Modal Konfirmasi Ambil Saldo -->
                        <div class="modal fade" id="modalAmbilSaldo" tabindex="-1" aria-labelledby="modalAmbilSaldoLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalAmbilSaldoLabel">Konfirmasi Ambil Saldo</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah Anda yakin ingin mengambil saldo untuk transaksi ini?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="button" class="btn btn-info" id="btnKonfirmasiAmbilSaldo">Ya, Ambil
                                            Saldo</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                @endif
                <!-- Modal Upload Bukti Transfer -->
                <div class="modal fade" id="modalTransfer" tabindex="-1" aria-labelledby="modalTransferLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTransferLabel">Upload Bukti Transfer</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form id="formBuktiTransfer">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="buktiTransfer" class="form-label">Bukti Transfer (gambar)</label>
                                        <input class="form-control" type="file" id="buktiTransfer" name="bukti_transfer"
                                            accept="image/*" onchange="previewBuktiTransfer(event)">
                                    </div>
                                    <div id="previewBukti" style="display:none;">
                                        <label class="form-label">Preview:</label>
                                        <img id="imgPreviewBukti" src="#" alt="Preview Bukti Transfer"
                                            class="img-fluid rounded border" style="max-height:250px;" />
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Upload</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
                    function showLoading(show) {
                        var loading = document.getElementById('loadingApi');
                        if (loading) loading.style.display = show ? 'flex' : 'none';
                    }

                    document.getElementById('btnKonfirmasiAmbilSaldo').addEventListener('click', function() {
                        var transaksiId = {{ $transaksi->id }};
                        var url = '/api/transaksi-lapak/' + transaksiId + '/ambil-saldo';
                        showLoading(true);
                        fetch(url, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                showLoading(false);
                                if (data.status) {
                                    alert('Saldo berhasil diambil!');
                                    document.getElementById('modalAmbilSaldo').querySelector('.btn-close').click();
                                    // window.location.reload();
                                    window.location.href = '/admin/setor-lapak'; // Redirect ke halaman admin/setor-lapak
                                } else {
                                    alert('Gagal ambil saldo: ' + (data.message || 'Coba lagi.'));
                                }
                            })
                            .catch(err => {
                                showLoading(false);
                                alert('Terjadi kesalahan saat ambil saldo.');
                            });
                    });

                    function previewBuktiTransfer(event) {
                        const input = event.target;
                        const previewDiv = document.getElementById('previewBukti');
                        const img = document.getElementById('imgPreviewBukti');
                        if (input.files && input.files[0]) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                img.src = e.target.result;
                                previewDiv.style.display = 'block';
                            };
                            reader.readAsDataURL(input.files[0]);
                        } else {
                            img.src = '#';
                            previewDiv.style.display = 'none';
                        }
                    }

                    document.getElementById('formBuktiTransfer').addEventListener('submit', function(e) {
                        e.preventDefault();
                        var form = e.target;
                        var formData = new FormData(form);
                        var transaksiId = {{ $transaksi->id }};
                        var url = '/api/transaksi-lapak/' + transaksiId + '/upload-bukti-transfer';
                        var btnUpload = form.querySelector('button[type="submit"]');
                        btnUpload.disabled = true;
                        showLoading(true);
                        fetch(url, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                btnUpload.disabled = false;
                                showLoading(false);
                                if (data.status) {
                                    alert('Bukti transfer berhasil diupload!');
                                    document.getElementById('modalTransfer').querySelector('.btn-close').click();
                                    window.location.href = '/admin/setor-lapak'; // Redirect ke halaman admin/setor-lapak
                                } else {
                                    alert('Gagal upload: ' + (data.message || 'Cek file dan coba lagi.'));
                                }
                            })
                            .catch(err => {
                                btnUpload.disabled = false;
                                showLoading(false);
                                alert('Terjadi kesalahan saat upload.');
                            });
                    });
                </script>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3 mb-2">
                    <div class="col-md-4">
                        <div class="border rounded-3 p-2 h-100 bg-light">
                            <div class="mb-1"><i class="bi bi-upc-scan"></i> <strong>Kode Transaksi:</strong><br> <span class="text-muted">{{ $transaksi->kode_transaksi }}</span></div>
                            <div class="mb-1"><i class="bi bi-calendar-event"></i> <strong>Tanggal:</strong><br> <span class="text-muted">{{ $transaksi->tanggal_transaksi }}</span></div>
                            <div><i class="bi bi-info-circle"></i> <strong>Status:</strong><br> <span class="badge bg-warning text-dark">{{ $transaksi->status }}</span></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded-3 p-2 h-100 bg-light">
                            <div class="mb-1"><i class="bi bi-cash-coin"></i> <strong>Jumlah Total:</strong><br> <span class="text-success fw-bold">Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}</span></div>
                            <div><i class="bi bi-person-badge"></i> <strong>Petugas:</strong><br> <span class="text-muted">{{ $transaksi->petugas->nama ?? '-' }}</span></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded-3 p-2 h-100 bg-light">
                            <div class="mb-1"><i class="bi bi-bank2"></i> <strong>Bank:</strong><br> <span class="text-muted">{{ $transaksi->jenisMetodePenarikan->nama ?? '-' }}</span></div>
                            <div class="mb-1"><i class="bi bi-person"></i> <strong>Atas Nama:</strong><br> <span class="text-muted">{{ $transaksi->lapak->nama_rekening ?? '-' }}</span></div>
                            <div><i class="bi bi-credit-card-2-front"></i> <strong>No Rekening:</strong><br> <span class="text-muted">{{ $transaksi->lapak->nomor_rekening ?? '-' }}</span></div>
                        </div>
                    </div>
                </div>
                <hr>
                <h5 class="mb-3"><i class="bi bi-list-ul"></i> Detail Item Transaksi</h5>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">#</th>
                                <th>Nama Sampah</th>
                                <th class="text-center">Berat (kg)</th>
                                <th class="text-end">Harga per Kg</th>
                                <th class="text-end">Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksi->detail_transaksi as $idx => $detail)
                                <tr>
                                    <td class="text-center">{{ $idx + 1 }}</td>
                                    <td>{{ $detail->sampah->nama_sampah ?? '-' }}</td>
                                    <td class="text-center">{{ $detail->berat_kg }}</td>
                                    <td class="text-end">Rp {{ number_format($detail->harga_per_kg, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($detail->total_harga, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

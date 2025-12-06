@extends('layouts.template')

@section('title', 'Detail Transaksi Lapak')

@section('main')
<div class="container py-4">
    <div class="row mb-3">
        <div class="col-12">
            <h3 class="fw-bold">Detail Transaksi Lapak</h3>
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm mb-2"><i class="bi bi-arrow-left"></i> Kembali</a>
            <a href="{{ route('admin.lapak.transaksi.download', $transaksi->id) }}" class="btn btn-success btn-sm mb-2"><i class="bi bi-download"></i> Download Detail</a>
            <!-- Tombol Pencairan Data -->
            <fieldset class="mb-2">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTransfer">
                    <i class="bi bi-bank"></i> Transfer
                </button>
                <button type="button" class="btn btn-info btn-sm" onclick="handlePencairan('saldo')">
                    <i class="bi bi-wallet2"></i> Ambil dari Saldo
                </button>
            </fieldset>

            <!-- Modal Upload Bukti Transfer -->
            <div class="modal fade" id="modalTransfer" tabindex="-1" aria-labelledby="modalTransferLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modalTransferLabel">Upload Bukti Transfer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form id="formBuktiTransfer">
                  <div class="modal-body">
                    <div class="mb-3">
                      <label for="buktiTransfer" class="form-label">Bukti Transfer (gambar)</label>
                      <input class="form-control" type="file" id="buktiTransfer" name="bukti_transfer" accept="image/*" onchange="previewBuktiTransfer(event)">
                    </div>
                    <div id="previewBukti" style="display:none;">
                      <label class="form-label">Preview:</label>
                      <img id="imgPreviewBukti" src="#" alt="Preview Bukti Transfer" class="img-fluid rounded border" style="max-height:250px;"/>
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
                function handlePencairan(type) {
                    if(type === 'saldo') {
                        alert('Pencairan dari saldo dipilih.');
                        // TODO: Implementasi aksi ambil dari saldo
                    }
                }

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
                        if(data.status) {
                            alert('Bukti transfer berhasil diupload!');
                            document.getElementById('modalTransfer').querySelector('.btn-close').click();
                        } else {
                            alert('Gagal upload: ' + (data.message || 'Cek file dan coba lagi.'));
                        }
                    })
                    .catch(err => {
                        btnUpload.disabled = false;
                        alert('Terjadi kesalahan saat upload.');
                    });
                });
            </script>
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

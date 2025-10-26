@extends('layouts.template')

@section('title', 'Setoran Sampah')

@section('style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-5-theme/1.2.0/select2-bootstrap.min.css" rel="stylesheet">
@endsection

@section('main')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">Formulir Setoran Sampah</h3>
        <h6 class="op-7 mb-2">Isi formulir di bawah untuk mencatat setoran sampah.</h6>
    </div>
</div>

<div class="row">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <form action="{{ route('petugas.transaksi.store') }}" method="POST">
            @csrf
            <div class="card mb-3">
                <div class="card-body">
                    <div class="form-group">
                        <label>Kode Setoran</label>
                        <input class="form-control" type="text" name="kode_transaksi" value="{{ $kodeTransaksi }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nasabah</label>
                        <input class="form-control" type="text" name="nasabah" value="{{ $nasabah->nama_lengkap ?? '' }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>No. Registrasi</label>
                        <input class="form-control" type="text" name="no_registrasi" value="{{ $nasabah->no_registrasi ?? '' }}" readonly>
                    </div>
                    <input type="hidden" name="nasabah_id" value="{{ $nasabah->id ?? '' }}">
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <div class="form-group">
                        <label>Tanggal Setoran</label>
                        <input type="date" name="tanggal_transaksi" value="{{ date('Y-m-d') }}" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <div class="form-group">
                        <label>Detail Setoran</label>
                        <table class="table table-bordered" id="setoran-detail-table">
                            <thead>
                                <tr>
                                    <th>Jenis Sampah</th>
                                    <th>Berat (kg)</th>
                                    <th>Harga per kg (Rp)</th>
                                    <th>Total (Rp)</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="setoran-details">
                                <tr>
                                    <td>
                                        <select name="detail_transaksi[0][sampah_id]" class="form-control" required>
                                            <option value="">-- Pilih Sampah --</option>
                                            @foreach ($stokSampah as $sampah)
                                                <option value="{{ $sampah->id }}" data-harga="{{ $sampah->harga_per_kg }}">
                                                    {{ $sampah->nama_sampah }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" name="detail_transaksi[0][berat_kg]" class="form-control berat-kg" placeholder="Berat (kg)" required></td>
                                    <td><input type="number" name="detail_transaksi[0][harga_per_kg]" class="form-control harga-per-kg" placeholder="Harga per kg" required readonly></td>
                                    <td class="total-harga" data-total="0">0</td>
                                    <td><button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-success" id="add-row">Tambah Sampah</button>
                    </div>

                    <div class="form-group mt-3">
                        <label>Total Transaksi (Rp)</label>
                        <input type="text" id="total-transaksi" class="form-control" value="0" readonly>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">Simpan Setoran</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2-nasabah').select2({
        theme: 'bootstrap-5',
        placeholder: '-- Pilih Nasabah --',
        allowClear: true
    });

    let rowIndex = 1;

    // Update total per baris
    function updateTotal(row) {
        let hargaPerKg = parseFloat(row.find('input[name*="harga_per_kg"]').val()) || 0;
        let berat = parseFloat(row.find('input[name*="berat_kg"]').val()) || 0;
        let totalHarga = hargaPerKg * berat;

        row.find('.total-harga').data('total', totalHarga);
        row.find('.total-harga').text(totalHarga.toLocaleString('id-ID'));
        calculateTotalTransaksi();
    }

    // Hitung total transaksi
    function calculateTotalTransaksi() {
        let total = 0;
        $('#setoran-details .total-harga').each(function() {
            total += parseFloat($(this).data('total')) || 0;
        });
        $('#total-transaksi').val(total.toLocaleString('id-ID'));
    }

    // Tambah baris
    $('#add-row').on('click', function() {
        let newRow = `
        <tr>
            <td>
                <select name="detail_transaksi[${rowIndex}][sampah_id]" class="form-control" required>
                    <option value="">-- Pilih Sampah --</option>
                    @foreach ($stokSampah as $sampah)
                        <option value="{{ $sampah->id }}" data-harga="{{ $sampah->harga_per_kg }}">
                            {{ $sampah->nama_sampah }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="detail_transaksi[${rowIndex}][berat_kg]" class="form-control berat-kg" placeholder="Berat (kg)" required></td>
            <td><input type="number" name="detail_transaksi[${rowIndex}][harga_per_kg]" class="form-control harga-per-kg" placeholder="Harga per kg" required readonly></td>
            <td class="total-harga" data-total="0">0</td>
            <td><button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button></td>
        </tr>`;
        $('#setoran-details').append(newRow);
        rowIndex++;
    });

    // Hapus baris
    $(document).on('click', '.remove-row', function() {
        $(this).closest('tr').remove();
        calculateTotalTransaksi();
    });

    // Update harga & total saat pilih sampah atau ubah berat
    $(document).on('change', 'select[name*="sampah_id"], input[name*="berat_kg"]', function() {
        let row = $(this).closest('tr');
        let selectedOption = row.find('select[name*="sampah_id"]').find(':selected');
        let hargaPerKg = selectedOption.data('harga') || 0;
        row.find('input[name*="harga_per_kg"]').val(hargaPerKg);
        updateTotal(row);
    });
});
</script>
@endsection

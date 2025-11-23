@extends('layouts.template')

@section('title', 'Setor Sampah Lapak')

@section('main')
@push('style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-5-theme/1.2.0/select2-bootstrap.min.css" rel="stylesheet">
@endpush

<div class="container py-4">
    <h3 class="fw-bold mb-3">Setor Sampah Lapak</h3>
    <div class="card mb-3 card-info">
        <div class="card-header">
            <h4 class="card-title">Informasi Lapak</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama Lapak</label>
                        <input class="form-control" type="text" value="{{ $lapak->nama_lapak }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Kode Lapak</label>
                        <input class="form-control" type="text" value="{{ $lapak->kode_lapak }}" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Cabang</label>
                        <input class="form-control" type="text" value="{{ $lapak->cabang->nama_cabang ?? '-' }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <input class="form-control" type="text" value="{{ $lapak->alamat }}" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="#">
                @csrf
                <div class="mb-3">
                    <label for="kode_transaksi" class="form-label">Kode Transaksi</label>
                    <input type="text" class="form-control" id="kode_transaksi" name="kode_transaksi" value="{{ 'TRXLPK-' . date('YmdHis') }}" readonly>
                </div>
                <div class="mb-3">
                    <label for="tanggal_transaksi" class="form-label">Tanggal Setoran</label>
                    <input type="date" class="form-control" id="tanggal_transaksi" name="tanggal_transaksi" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="table-responsive mb-3">
                    <table class="table table-bordered" id="setoran-detail-table">
                        <thead class="table-light">
                            <tr>
                                <th width="35%">Jenis Sampah</th>
                                <th width="15%">Berat (kg)</th>
                                <th width="20%">Harga per kg (Rp)</th>
                                <th width="20%">Total (Rp)</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="setoran-details">
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5">
                                    <button type="button" class="btn btn-success btn-sm" id="add-row">
                                        <i class="fas fa-plus"></i> Tambah Item Sampah
                                    </button>
                                </td>
                            </tr>
                            <tr class="table-light">
                                <td colspan="3" class="text-end"><strong>Total Keseluruhan:</strong></td>
                                <td colspan="2" class="text-end">
                                    <strong id="total-transaksi-display">Rp 0</strong>
                                    <input type="hidden" id="total-transaksi" name="total_transaksi" value="0">
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="text-end">
                    <a href="{{ route('petugas.lapak.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Setoran</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    let rowIndex = 0;
    let stokSampah = @json(\App\Models\Sampah::all());

    function updateTotal(row) {
        let hargaPerKg = parseFloat(row.find('input[name*="harga_per_kg"]').val()) || 0;
        let berat = parseFloat(row.find('input[name*="berat_kg"]').val()) || 0;
        let totalHarga = hargaPerKg * berat;
        row.find('.total-harga').data('total', totalHarga);
        row.find('.total-harga').text('Rp ' + totalHarga.toLocaleString('id-ID'));
        calculateTotalTransaksi();
    }

    function calculateTotalTransaksi() {
        let total = 0;
        $('#setoran-details .total-harga').each(function() {
            total += parseFloat($(this).data('total')) || 0;
        });
        $('#total-transaksi').val(total);
        $('#total-transaksi-display').text('Rp ' + total.toLocaleString('id-ID'));
    }

    $('#add-row').on('click', function() {
        let options = '<option value="">-- Pilih Sampah --</option>';
        stokSampah.forEach(function(sampah) {
            options += `<option value="${sampah.id}" data-harga="${sampah.harga_lapak}">${sampah.nama_sampah}</option>`;
        });
        let newRow = `
        <tr>
            <td>
                <select name="detail_transaksi[${rowIndex}][sampah_id]" class="form-control jenis-sampah-select" required>${options}</select>
            </td>
            <td>
                <input type="number" name="detail_transaksi[${rowIndex}][berat_kg]" class="form-control berat-kg" placeholder="0.00" step="0.01" min="0" required>
            </td>
            <td>
                <input type="number" name="detail_transaksi[${rowIndex}][harga_per_kg]" class="form-control harga-per-kg" placeholder="0" readonly>
            </td>
            <td class="total-harga text-end" data-total="0">Rp 0</td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button>
            </td>
        </tr>`;
        $('#setoran-details').append(newRow);
        rowIndex++;
    });

    // Event: pilih jenis sampah
    $(document).on('change', '.jenis-sampah-select', function() {
        let harga = $(this).find('option:selected').data('harga') || 0;
        let row = $(this).closest('tr');
        row.find('.harga-per-kg').val(harga);
        updateTotal(row);
    });

    // Event: input berat
    $(document).on('input', '.berat-kg', function() {
        let row = $(this).closest('tr');
        updateTotal(row);
    });

    // Event: hapus baris
    $(document).on('click', '.remove-row', function() {
        $(this).closest('tr').remove();
        calculateTotalTransaksi();
    });
});
</script>
@endpush
@endsection

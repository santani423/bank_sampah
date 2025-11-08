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
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('petugas.transaksi.store') }}" method="POST">
            @csrf
            
            <!-- Card Informasi Nasabah -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Nasabah</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Kode Setoran</label>
                                <input class="form-control" type="text" name="kode_transaksi" value="{{ $kodeTransaksi }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Nama Nasabah</label>
                                <input class="form-control" type="text" name="nasabah" value="{{ $nasabah->nama_lengkap ?? '' }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">No. Registrasi</label>
                                <input class="form-control" type="text" name="no_registrasi" value="{{ $nasabah->no_registrasi ?? '' }}" readonly>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="nasabah_id" value="{{ $nasabah->id ?? '' }}">
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label">Tanggal Setoran <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_transaksi" value="{{ date('Y-m-d') }}" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Detail Setoran -->
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Detail Setoran Sampah</h5>
                    <button type="button" class="btn btn-success btn-sm" id="add-row">
                        <i class="fas fa-plus"></i> Tambah Item
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="setoran-detail-table">
                            <thead class="table-light">
                                <tr>
                                    <th width="35%">Jenis Sampah</th>
                                    <th width="15%">Berat (kg)</th>
                                    <th width="20%">Harga per kg (Rp)</th>
                                    <th width="20%">Total (Rp)</th>
                                    <th width="10%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="setoran-details">
                                <tr>
                                    <td>
                                        <select name="detail_transaksi[0][sampah_id]" class="form-control form-control-sm" required>
                                            <option value="">-- Pilih Sampah --</option>
                                            @foreach ($stokSampah as $sampah)
                                                <option value="{{ $sampah->id }}" data-harga="{{ $sampah->harga_per_kg }}">
                                                    {{ $sampah->nama_sampah }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="detail_transaksi[0][berat_kg]" class="form-control form-control-sm berat-kg" placeholder="0.00" required>
                                    </td>
                                    <td>
                                        <input type="number" name="detail_transaksi[0][harga_per_kg]" class="form-control form-control-sm harga-per-kg" placeholder="0" readonly>
                                    </td>
                                    <td class="text-end total-harga" data-total="0">Rp 0</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm remove-row" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total Transaksi:</strong></td>
                                    <td colspan="2">
                                        <input type="text" id="total-transaksi" class="form-control form-control-sm fw-bold" value="Rp 0" readonly>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('petugas.transaksi.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Setoran
                    </button>
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
    let rowIndex = 1;

    // Format currency
    function formatRupiah(angka) {
        return 'Rp ' + angka.toLocaleString('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
    }

    // Update total per baris
    function updateTotal(row) {
        let hargaPerKg = parseFloat(row.find('input[name*="harga_per_kg"]').val()) || 0;
        let berat = parseFloat(row.find('input[name*="berat_kg"]').val()) || 0;
        let totalHarga = hargaPerKg * berat;

        row.find('.total-harga').data('total', totalHarga);
        row.find('.total-harga').text(formatRupiah(totalHarga));
        calculateTotalTransaksi();
    }

    // Hitung total transaksi
    function calculateTotalTransaksi() {
        let total = 0;
        $('#setoran-details .total-harga').each(function() {
            total += parseFloat($(this).data('total')) || 0;
        });
        $('#total-transaksi').val(formatRupiah(total));
    }

    // Tambah baris
    $('#add-row').on('click', function() {
        let newRow = `
        <tr>
            <td>
                <select name="detail_transaksi[${rowIndex}][sampah_id]" class="form-control form-control-sm" required>
                    <option value="">-- Pilih Sampah --</option>
                    @foreach ($stokSampah as $sampah)
                        <option value="{{ $sampah->id }}" data-harga="{{ $sampah->harga_per_kg }}">
                            {{ $sampah->nama_sampah }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" step="0.01" name="detail_transaksi[${rowIndex}][berat_kg]" class="form-control form-control-sm berat-kg" placeholder="0.00" required>
            </td>
            <td>
                <input type="number" name="detail_transaksi[${rowIndex}][harga_per_kg]" class="form-control form-control-sm harga-per-kg" placeholder="0" readonly>
            </td>
            <td class="text-end total-harga" data-total="0">Rp 0</td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm remove-row" title="Hapus">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>`;
        $('#setoran-details').append(newRow);
        rowIndex++;
    });

    // Hapus baris
    $(document).on('click', '.remove-row', function() {
        if ($('#setoran-details tr').length > 1) {
            $(this).closest('tr').remove();
            calculateTotalTransaksi();
        } else {
            alert('Minimal harus ada satu item setoran!');
        }
    });

    // Update harga & total saat pilih sampah atau ubah berat
    $(document).on('change', 'select[name*="sampah_id"]', function() {
        let row = $(this).closest('tr');
        let selectedOption = $(this).find(':selected');
        let hargaPerKg = selectedOption.data('harga') || 0;
        row.find('input[name*="harga_per_kg"]').val(hargaPerKg);
        updateTotal(row);
    });

    $(document).on('input', 'input[name*="berat_kg"]', function() {
        let row = $(this).closest('tr');
        updateTotal(row);
    });

    // Validasi form sebelum submit
    $('form').on('submit', function(e) {
        let hasDetail = $('#setoran-details tr').length > 0;
        if (!hasDetail) {
            e.preventDefault();
            alert('Minimal harus ada satu item setoran!');
            return false;
        }
        
        // Check if all items have valid data
        let isValid = true;
        $('#setoran-details tr').each(function() {
            let sampahId = $(this).find('select[name*="sampah_id"]').val();
            let berat = $(this).find('input[name*="berat_kg"]').val();
            
            if (!sampahId || !berat || parseFloat(berat) <= 0) {
                isValid = false;
                return false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Pastikan semua item memiliki jenis sampah dan berat yang valid!');
            return false;
        }
    });
});
</script>
@endsection

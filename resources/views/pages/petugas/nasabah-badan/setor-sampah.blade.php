@extends('layouts.template')

@section('title', 'Setoran Sampah Nasabah Badan')

@push('style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-5-theme/1.2.0/select2-bootstrap.min.css" rel="stylesheet">
<style>
    .card-info { background-color: #f8f9fa; }
</style>
@endpush

@section('main')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">Formulir Setoran Sampah</h3>
        <h6 class="op-7 mb-2">Isi formulir di bawah untuk mencatat setoran sampah dari nasabah badan.</h6>
    </div>
    <div class="ms-md-auto py-2 py-md-0">
        <a href="{{ route('petugas.rekanan.index') }}" class="btn btn-secondary btn-round">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
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

        <!-- Form Import CSV & Download Template -->
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><strong>Import Data Sampah dari Excel/CSV</strong></span>
                <div class="d-flex gap-2">
          
                    <a href="{{ asset('template_import_sampah.csv') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-download"></i> Download Template (File)
                    </a>
                    {{-- <button type="button" id="download-template-js" class="btn btn-primary btn-sm">
                        <i class="fas fa-magic"></i> Download Template (JS)
                    </button>
                    <button type="button" id="commit-preview" class="btn btn-warning btn-sm" disabled>
                        <i class="fas fa-check"></i> Masukkan Preview ke Form
                    </button> --}}
                </div>
            </div>
            <div class="card-body">
                <form id="form-import-sampah" action="{{ route('petugas.rekanan.sampah-import', $nasabahBadan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row align-items-end">
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label for="file_import">Pilih File Excel/CSV</label>
                                <input type="file" name="file_import" id="file_import" class="form-control" accept=".csv,.xlsx,.xls" required>
                                <small class="text-muted">Format CSV: kode_sampah, nama_sampah, qty. Data akan dipreview dulu (tidak langsung disimpan).</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary" id="btn-preview-import">
                                <i class="fas fa-eye"></i> Preview Import
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <form action="{{ route('petugas.rekanan.setor-sampah.store', $nasabahBadan->id) }}" method="POST" id="form-setor-sampah">
            @csrf
            
            <!-- Informasi Nasabah Badan -->
            <div class="card mb-3 card-info">
                <div class="card-header">
                    <h4 class="card-title">Informasi Nasabah Badan</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Badan</label>
                                <input class="form-control" type="text" value="{{ $nasabahBadan->nama_badan }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>Jenis Badan</label>
                                <input class="form-control" type="text" value="{{ $nasabahBadan->jenisBadan->nama ?? '-' }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>NIB</label>
                                <input class="form-control" type="text" value="{{ $nasabahBadan->nib ?? '-' }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>No. Telepon</label>
                                <input class="form-control" type="text" value="{{ $nasabahBadan->no_telp ?? '-' }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Transaksi -->
            <div class="card mb-3">
                <div class="card-header">
                    <h4 class="card-title">Informasi Transaksi</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kode Transaksi <span class="text-danger">*</span></label>
                                <input class="form-control @error('kode_transaksi') is-invalid @enderror" 
                                       type="text" 
                                       name="kode_transaksi" 
                                       value="{{ old('kode_transaksi', $kodeTransaksi) }}" 
                                       readonly>
                                @error('kode_transaksi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Setoran <span class="text-danger">*</span></label>
                                <input type="date" 
                                       name="tanggal_transaksi" 
                                       value="{{ old('tanggal_transaksi', date('Y-m-d')) }}" 
                                       class="form-control @error('tanggal_transaksi') is-invalid @enderror" 
                                       required>
                                @error('tanggal_transaksi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Setoran -->
            <div class="card mb-3">
                <div class="card-header">
                    <h4 class="card-title">Detail Setoran Sampah</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
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
                                    <td>
                                        <input type="number" 
                                               name="detail_transaksi[0][berat_kg]" 
                                               class="form-control berat-kg" 
                                               placeholder="0.00" 
                                               step="0.01"
                                               min="0"
                                               required>
                                    </td>
                                    <td>
                                        <input type="number" 
                                               name="detail_transaksi[0][harga_per_kg]" 
                                               class="form-control harga-per-kg" 
                                               placeholder="0" 
                                               readonly>
                                    </td>
                                    <td class="total-harga text-end" data-total="0">Rp 0</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm remove-row" disabled>
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
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
                                        <input type="hidden" id="total-transaksi" value="0">
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="card-footer text-end">
                    <a href="{{ route('petugas.rekanan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary" id="btn-submit">
                        <i class="fas fa-save"></i> <span class="label">Simpan Setoran</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    let rowIndex = 1;

    // Initialize select2
    $('.sampah-select').select2({
        theme: 'bootstrap-5',
        placeholder: '-- Pilih Sampah --',
        allowClear: true,
        width: '100%'
    });

    // Update total per baris
    function updateTotal(row) {
        let hargaPerKg = parseFloat(row.find('input[name*="harga_per_kg"]').val()) || 0;
        let berat = parseFloat(row.find('input[name*="berat_kg"]').val()) || 0;
        let totalHarga = hargaPerKg * berat;

        row.find('.total-harga').data('total', totalHarga);
        row.find('.total-harga').text('Rp ' + totalHarga.toLocaleString('id-ID'));
        calculateTotalTransaksi();
    }

    // Hitung total transaksi
    function calculateTotalTransaksi() {
        let total = 0;
        $('#setoran-details .total-harga').each(function() {
            total += parseFloat($(this).data('total')) || 0;
        });
        $('#total-transaksi').val(total);
        $('#total-transaksi-display').text('Rp ' + total.toLocaleString('id-ID'));
    }

    // Toggle remove button
    function toggleRemoveButtons() {
        const rowCount = $('#setoran-details tr').length;
        if (rowCount === 1) {
            $('.remove-row').prop('disabled', true);
        } else {
            $('.remove-row').prop('disabled', false);
        }
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
            <td>
                <input type="number" 
                       name="detail_transaksi[${rowIndex}][berat_kg]" 
                       class="form-control berat-kg" 
                       placeholder="0.00" 
                       step="0.01"
                       min="0"
                       required>
            </td>
            <td>
                <input type="number" 
                       name="detail_transaksi[${rowIndex}][harga_per_kg]" 
                       class="form-control harga-per-kg" 
                       placeholder="0" 
                       readonly>
            </td>
            <td class="total-harga text-end" data-total="0">Rp 0</td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm remove-row">
                   Hapus
                </button>
            </td>
        </tr>`;
        $('#setoran-details').append(newRow);
        
        // Initialize select2 on new row
        $('#setoran-details tr:last .sampah-select').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Pilih Sampah --',
            allowClear: true,
            width: '100%'
        });
        
        rowIndex++;
        toggleRemoveButtons();
    });

    // Hapus baris
    $(document).on('click', '.remove-row', function() {
        if ($('#setoran-details tr').length > 1) {
            $(this).closest('tr').remove();
            calculateTotalTransaksi();
            toggleRemoveButtons();
        }
    });

    // Update harga & total saat pilih sampah
    $(document).on('change', 'select[name*="sampah_id"]', function() {
        let row = $(this).closest('tr');
        let selectedOption = $(this).find(':selected');
        let hargaPerKg = selectedOption.data('harga') || 0;
        row.find('input[name*="harga_per_kg"]').val(hargaPerKg);
        updateTotal(row);
    });

    // Update total saat ubah berat
    $(document).on('input', 'input[name*="berat_kg"]', function() {
        let row = $(this).closest('tr');
        updateTotal(row);
    });

    // Form submit with loading
    $('#form-setor-sampah').on('submit', function() {
        const btn = $('#btn-submit');
        btn.prop('disabled', true);
        btn.find('.label').addClass('d-none');
        btn.find('.spinner-border').removeClass('d-none');
    });

    // Initial toggle
    toggleRemoveButtons();

    // Preview import (client-side) - only for CSV
    $('#form-import-sampah').on('submit', function(e) {
        e.preventDefault();
        const fileInput = document.getElementById('file_import');
        if(!fileInput.files.length){
            alert('Pilih file terlebih dahulu.');
            return;
        }
        const file = fileInput.files[0];
        const ext = file.name.split('.').pop().toLowerCase();
        if(ext !== 'csv'){ 
            alert('Preview cepat hanya mendukung file CSV. Untuk XLS/XLSX silakan konversi ke CSV terlebih dahulu.');
            return; 
        }
        const reader = new FileReader();
        reader.onload = function(evt){
            const text = evt.target.result;
            const lines = text.split(/\r?\n/).filter(l => l.trim() !== '');
            if(lines.length < 2){
                alert('File tidak memiliki data yang cukup.');
                return;
            }
            // Parse header
            const header = lines[0].split(',').map(h => h.replace(/(^\"|\"$)/g,'').trim().toLowerCase());
            const idxKode = header.indexOf('kode_sampah');
            const idxNama = header.indexOf('nama_sampah');
            const idxQty = header.indexOf('qty');
            if(idxNama === -1 || idxQty === -1){
                alert('Kolom wajib: nama_sampah, qty (kode_sampah opsional).');
                return;
            }
            // Clear existing preview rows appended earlier
            $('.preview-import-row').remove();
            // Add rows to table for preview (not saved yet)
            let previewCount = 0;
            lines.slice(1).forEach((line,i) => {
                const cols = line.split(',').map(c => c.replace(/(^\"|\"$)/g,'').trim());
                if(cols.length === 0 || cols.every(c => c === '')) return;
                const kode = idxKode !== -1 ? cols[idxKode] : '';
                const nama = cols[idxNama] || '';
                const qty = cols[idxQty] || '0';
                if(!nama) return; // skip empty name rows
                // Append an editable preview row
                const newRow = `
                <tr class="preview-import-row table-warning">
                    <td>
                        <div class="small text-muted">PREVIEW</div>
                        <select class="form-control preview-sampah-select">
                            <option value="">-- Pilih Sampah --</option>
                            @foreach ($stokSampah as $sampah)
                                <option value="{{ $sampah->id }}" data-harga="{{ $sampah->harga_per_kg }}">{{ $sampah->nama_sampah }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" class="form-control preview-berat" value="${parseFloat(qty) || 0}" step="0.01" min="0"></td>
                    <td><input type="number" class="form-control preview-harga" value="0"></td>
                    <td class="total-harga text-end">Rp 0</td>
                    <td class="text-center"><button type="button" class="btn btn-sm btn-secondary" disabled>Preview</button></td>
                </tr>`;
                $('#setoran-details').append(newRow);
                previewCount++;
                // Auto select matching sampah by name
                const lastSelect = $('#setoran-details tr:last select.preview-sampah-select');
                lastSelect.find('option').each(function(){
                    if($(this).text().trim().toLowerCase() === nama.trim().toLowerCase()){
                        lastSelect.val($(this).val());
                        // Auto-fill harga from selected option
                        const hargaPerKg = $(this).data('harga') || 0;
                        $('#setoran-details tr:last input.preview-harga').val(hargaPerKg);
                        return false;
                    }
                });
            });
            if(previewCount === 0){
                alert('Tidak ada baris data yang valid untuk dipreview.');
            } else {
                alert('Preview berhasil ditampilkan. Edit angka berat/harga jika perlu lalu tekan Masukkan Preview ke Form.');
                $('#commit-preview').prop('disabled', false);
            }
        };
        reader.readAsText(file);
    });

    // Generate & download template CSV via JavaScript
    // Commit preview rows into real form rows
    $('#commit-preview').on('click', function(){
        const previewRows = $('.preview-import-row');
        if(previewRows.length === 0){
            alert('Tidak ada baris preview untuk dimasukkan.');
            return;
        }
        let inserted = 0;
        previewRows.each(function(){
            const selectEl = $(this).find('select.preview-sampah-select');
            const sampahId = selectEl.val();
            const beratVal = $(this).find('input.preview-berat').val();
            const hargaVal = $(this).find('input.preview-harga').val();
            const newRow = `
            <tr>
                <td>
                    <select name="detail_transaksi[${rowIndex}][sampah_id]" class="form-control" required>
                        <option value="">-- Pilih Sampah --</option>
                        @foreach ($stokSampah as $sampah)
                            <option value="{{ $sampah->id }}" data-harga="{{ $sampah->harga_per_kg }}">{{ $sampah->nama_sampah }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="detail_transaksi[${rowIndex}][berat_kg]" class="form-control berat-kg" step="0.01" min="0" value="${parseFloat(beratVal)||0}" required>
                </td>
                <td>
                    <input type="number" name="detail_transaksi[${rowIndex}][harga_per_kg]" class="form-control harga-per-kg" value="${parseFloat(hargaVal)||0}" readonly>
                </td>
                <td class="total-harga text-end" data-total="0">Rp 0</td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button>
                </td>
            </tr>`;
            $('#setoran-details').append(newRow);
            if(sampahId){
                $('#setoran-details tr:last select').val(sampahId);
            }
            updateTotal($('#setoran-details tr:last'));
            rowIndex++;
            inserted++;
        });
        previewRows.remove();
        toggleRemoveButtons();
        calculateTotalTransaksi();
        $('#commit-preview').prop('disabled', true);
        alert(inserted + ' baris preview dimasukkan sebagai baris editable. Silakan ubah berat sebelum simpan.');
    });

    const jsTemplateBtn = document.getElementById('download-template-js');
    if (jsTemplateBtn) {
        jsTemplateBtn.addEventListener('click', function() {
            // Define columns and sample rows
            const rows = [
                ['kode_sampah','nama_sampah','qty'],
                ['SMP001','Plastik',20],
                ['SMP002','Kertas',15],
                ['SMP003','Logam',50]
            ];

            // Convert to CSV string
            const csvContent = rows.map(r => r.map(v => '"' + String(v).replace(/"/g,'""') + '"').join(',')).join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);

            // Create temporary link
            const a = document.createElement('a');
            a.href = url;
            a.download = 'template_import_sampah_js.csv';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        });
    }
});
</script>
@endpush

@extends('layouts.template')

@section('title', 'Pengiriman Sampah')

@section('main')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Form Pengiriman Sampah</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('petugas.lapak.proses-kirim-sampah', $lapak->id ?? '') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="tanggal_pengiriman" class="form-label">Tanggal Pengiriman</label>
                            <input type="date" name="tanggal_pengiriman" id="tanggal_pengiriman" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="jenis_sampah" class="form-label">Jenis Sampah</label>
                            <input type="text" name="jenis_sampah" id="jenis_sampah" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="berat" class="form-label">Berat (kg)</label>
                            <input type="number" name="berat" id="berat" class="form-control" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea name="catatan" id="catatan" class="form-control" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Kirim Sampah</button>
                        <a href="{{ route('petugas.lapak.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

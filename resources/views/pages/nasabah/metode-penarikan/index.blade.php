@extends('layouts.template')

@section('title', 'Metode Penarikan Nasabah')

@push('style')
<style>
    .modal-header {
        color: white;
    }
</style>
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div class="me-auto">
            <h3 class="fw-bold mb-3">Metode Penarikan Nasabah</h3>
        </div>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                Tambah Metode Penarikan
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Daftar Metode Penarikan</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-head-bg-primary">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis Metode</th>
                                    <th>Atas Nama</th>
                                    <th>Nomor Rekening</th>
                                    {{-- <th>Aksi</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($metodePenarikan as $index => $metode)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $metode->jenisMetodePenarikan->nama }}</td>
                                        <td>{{ $metode->nama_metode_pencairan }}</td>
                                        <td>{{ $metode->no_rek }}</td>
                                        {{-- <td>
                                            <!-- Aksi Edit dan Hapus -->
                                            <a href="{{ route('nasabah.metode-penarikan.edit', $metode->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('nasabah.metode-penarikan.destroy', $metode->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($metodePenarikan->isEmpty())
                        <div class="text-center mt-3">Belum ada metode penarikan yang ditambahkan.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
    <!-- MODAL TAMBAH METODE PENARIKAN -->

    {{-- Modal Tambah Metode Penarikan --}}
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('nasabah.metode-penarikan.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white" id="modalTambahLabel">Tambah Metode Penarikan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        {{-- Jenis Metode --}}
                        <div class="mb-3">
                            <label for="jenis_metode_penarikan_id" class="form-label">Jenis Metode <span class="text-danger">*</span></label>
                            <select name="jenis_metode_penarikan_id" id="jenis_metode_penarikan_id" class="form-select @error('jenis_metode_penarikan_id') is-invalid @enderror" required>
                                <option value="">Pilih Jenis Metode</option>
                                @foreach ($jenisMetodePenarikan as $jenis)
                                    <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                                @endforeach
                            </select>
                            @error('jenis_metode_penarikan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nama Atas Nama --}}
                        <div class="mb-3">
                            <label for="nama_metode_pencairan" class="form-label">Atas Nama <span class="text-danger">*</span></label>
                            <input type="text" name="nama_metode_pencairan" id="nama_metode_pencairan" class="form-control @error('nama_metode_pencairan') is-invalid @enderror" required>
                            @error('nama_metode_pencairan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nomor Rekening --}}
                        <div class="mb-3">
                            <label for="no_rek" class="form-label">Nomor Rekening <span class="text-danger">*</span></label>
                            <input type="text" name="no_rek" id="no_rek" class="form-control @error('no_rek') is-invalid @enderror" required>
                            @error('no_rek')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Tambahan JavaScript jika diperlukan
</script>
@endpush

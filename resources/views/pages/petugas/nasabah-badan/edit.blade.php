@extends('layouts.template')

@section('title', 'Nasabah')

@push('style')
	<!-- CSS Libraries -->
@endpush

@section('main')
	<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
		<div>
			<h3 class="fw-bold mb-3">Form Edit Nasabah Badan</h3>
			<h6 class="op-7 mb-2">
				Ubah data nasabah badan pada form di bawah ini.
			</h6>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-header">
					<h4>Informasi Nasabah</h4>
				</div>
				<div class="card-body">
					<form action="{{ route('petugas.rekanan.update', $nasabahBadan->id) }}" method="POST" enctype="multipart/form-data" id="form-edit-nasabah-badan">
						@csrf
						@method('PUT')
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="jenis_badan_id">Jenis Badan <span class="text-danger">*</span></label>
									<select name="jenis_badan_id" id="jenis_badan_id" class="form-control @error('jenis_badan_id') is-invalid @enderror" required>
										<option value="">Pilih Jenis Badan</option>
										@foreach ($jenisBadans as $jenisBadan)
											<option value="{{ $jenisBadan->id }}" {{ old('jenis_badan_id', $nasabahBadan->jenis_badan_id) == $jenisBadan->id ? 'selected' : '' }}>
												{{ $jenisBadan->nama }}
											</option>
										@endforeach
									</select>
									@error('jenis_badan_id')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

								<div class="form-group">
									<label for="nama_badan">Nama Badan <span class="text-danger">*</span></label>
									<input type="text" class="form-control @error('nama_badan') is-invalid @enderror" 
										id="nama_badan" name="nama_badan" value="{{ old('nama_badan', $nasabahBadan->nama_badan) }}" required>
									@error('nama_badan')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

								<div class="form-group">
									<label for="npwp">NPWP</label>
									<input type="text" class="form-control @error('npwp') is-invalid @enderror" 
										id="npwp" name="npwp" value="{{ old('npwp', $nasabahBadan->npwp) }}">
									@error('npwp')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

								<div class="form-group">
									<label for="nib">NIB</label>
									<input type="text" class="form-control @error('nib') is-invalid @enderror" 
										id="nib" name="nib" value="{{ old('nib', $nasabahBadan->nib) }}">
									@error('nib')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label for="email">Email <span class="text-danger">*</span></label>
									<input type="email" class="form-control @error('email') is-invalid @enderror" 
										id="email" name="email" value="{{ old('email', $nasabahBadan->email) }}" required>
									@error('email')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

								<div class="form-group">
									<label for="username">Username <span class="text-danger">*</span></label>
									<input type="text" class="form-control @error('username') is-invalid @enderror" 
										id="username" name="username" value="{{ old('username', $nasabahBadan->username) }}" required>
									@error('username')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

								<div class="form-group">
									<label for="password">Password</label>
									<input type="password" class="form-control @error('password') is-invalid @enderror" 
										id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah">
									@error('password')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

								<div class="form-group">
									<label for="no_telp">Nomor Telepon</label>
									<input type="text" class="form-control @error('no_telp') is-invalid @enderror" 
										id="no_telp" name="no_telp" value="{{ old('no_telp', $nasabahBadan->no_telp) }}">
									@error('no_telp')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div>

							<div class="col-md-12">
								<div class="form-group">
									<label for="alamat_lengkap">Alamat Lengkap</label>
									<textarea class="form-control @error('alamat_lengkap') is-invalid @enderror" 
										id="alamat_lengkap" name="alamat_lengkap" rows="3">{{ old('alamat_lengkap', $nasabahBadan->alamat_lengkap) }}</textarea>
									@error('alamat_lengkap')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

								<div class="form-group">
									<label for="foto">Foto</label>
									<div class="mb-2">
										@php
											$fotoPath = $nasabahBadan->foto ? asset('storage/nasabah-badan/' . $nasabahBadan->foto) : null;
										@endphp
										@if ($nasabahBadan->foto)
											<img src="{{ $fotoPath }}" alt="Foto" class="img-thumbnail" style="max-height: 120px;">
										@endif
									</div>
									<input type="file" class="form-control @error('foto') is-invalid @enderror" 
										id="foto" name="foto">
									<small class="form-text text-muted">Kosongkan jika tidak ingin mengubah foto</small>
									@error('foto')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

								<div class="form-group">
									<label for="status">Status <span class="text-danger">*</span></label>
									<select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
										<option value="aktif" {{ old('status', $nasabahBadan->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
										<option value="tidak_aktif" {{ old('status', $nasabahBadan->status) == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
									</select>
									@error('status')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div>
						</div>

						<div class="form-group">
							<button type="submit" class="btn btn-primary" id="btn-submit">
								<span class="label">Simpan Perubahan</span>
								<span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
							</button>
							<a href="{{ route('petugas.rekanan.index') }}" class="btn btn-secondary">Kembali</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

@endsection

@push('scripts')
<script>
	// Minimal UX: disable submit to avoid double click and show tiny spinner
	document.getElementById('form-edit-nasabah-badan')?.addEventListener('submit', function() {
		const btn = document.getElementById('btn-submit');
		if (!btn) return;
		btn.disabled = true;
		btn.querySelector('.label')?.classList.add('d-none');
		btn.querySelector('.spinner-border')?.classList.remove('d-none');
	});
	// Auto-fill username if empty when name changes (only if username currently blank)
	const nama = document.getElementById('nama_badan');
	const username = document.getElementById('username');
	if (nama && username && !username.value) {
		nama.addEventListener('input', function() {
			if (!username.value) {
				username.value = this.value.toLowerCase().replace(/\s+/g, '');
			}
		});
	}
</script>
@endpush

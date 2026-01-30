<label for="jenis_metode_penarikan_id" class="form-label">Jenis Metode <span class="text-danger">*</span></label>
<select name="jenis_metode_penarikan_id" id="jenis_metode_penarikan_id"
    class="form-select @error('jenis_metode_penarikan_id') is-invalid @enderror" required>
    <option value="">Pilih Jenis Metode</option>
    @foreach ($metodeBayar as $jenis)
        <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
    @endforeach
</select>
@error('jenis_metode_penarikan_id')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror

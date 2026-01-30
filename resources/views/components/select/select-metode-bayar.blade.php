<label for="jenis_metode_penarikan_id" class="form-label">
    Jenis Metode @if($required)<span class="text-danger">*</span>@endif
</label>

<select
    name="jenis_metode_penarikan_id"
    id="jenis_metode_penarikan_id"
    class="form-select @error('jenis_metode_penarikan_id') is-invalid @enderror"
    @if($required) required @endif
>
    <option value="">Pilih Jenis Metode</option>

    @foreach ($metodeBayar as $jenis)
        <option
            value="{{ $jenis->id }}"
            {{ old('jenis_metode_penarikan_id') == $jenis->id ? 'selected' : '' }}
        >
            {{ $jenis->nama }}
        </option>
    @endforeach
</select>

@error('jenis_metode_penarikan_id')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
@enderror

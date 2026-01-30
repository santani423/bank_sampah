<label for="{{ $name }}" class="form-label">
    Jenis Metode
    @if ($required)
        <span class="text-danger">*</span>
    @endif
</label>

<select name="{{ $name }}" id="{{ $name }}" class="form-select @error($name) is-invalid @enderror"
    @if ($required) required @endif>
    <option value="">Pilih Jenis Metode</option>
    @php
        $selected = old($name, $value ?? '');
    @endphp
    @foreach ($metodeBayar as $jenis)
        <option value="{{ $jenis->id }}" {{ $selected == $jenis->id ? 'selected' : '' }}>
            {{ $jenis->nama }}
        </option>
    @endforeach
</select>

@error($name)
    <div class="invalid-feedback">
        {{ $message }}
    </div>
@enderror

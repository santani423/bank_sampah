 <label for="{{ $name }}">
     Collation Center @if ($required == 'true')
         <span class="text-danger">*</span>
     @endif
 </label>
 <select id="{{ $name }}" class="form-control @error($name) is-invalid @enderror" name="{{ $name }}"
     @if ($required == 'true') required @endif>
     <option value="" disabled selected>Pilih Collation Center</option>
     @php
         $selected = old($name, $value ?? '');
     @endphp
     @foreach ($cabang as $cabang)
         <option value="{{ $cabang->id }}" {{ $selected == $cabang->id ? 'selected' : '' }}>
             {{ $cabang->nama_cabang }} - {{ $cabang->kode_cabang }}
         </option>
     @endforeach
 </select>
 @error($name)
     <div class="invalid-feedback">{{ $message }}</div>
 @enderror

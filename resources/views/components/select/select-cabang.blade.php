 <label for="{{ $name }}">Cabang <span class="text-danger">*</span></label>
 <select id="{{ $name }}" class="form-control @error($name) is-invalid @enderror" name="{{ $name }}">
     <option value="" disabled selected>Pilih Cabang</option>
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

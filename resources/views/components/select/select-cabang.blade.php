  <label for="{{ $name }}" class="form-label">Cabang</label>
  <select class="form-control" name="{{ $name }}" id="{{ $name }}" aria-label="Default select example">
        <option value="">-- Pilih C --</option>
      @foreach ($cabang as $item)
          <option value="{{ $item->kode_cabang }}">{{ $item->nama_cabang }}</option>
      @endforeach
  </select>

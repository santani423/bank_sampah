  <label for="status_pengiriman" class="form-label">Status Pengiriman</label>
  <select class="form-control" name="{{ $name }}" id="{{ $name }}">
      <option value="">-- Pilih Status Pengiriman --</option>
      @php
          $selected = old($name, $value ?? '');
      @endphp

      <option value="pending" {{ $selected == 'pending' ? 'selected' : '' }}>Pending</option>
      <option value="dikirim" {{ $selected == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
      <option value="diterima" {{ $selected == 'diterima' ? 'selected' : '' }}>Diterima</option>
      <option value="batal" {{ $selected == 'batal' ? 'selected' : '' }}>Batal</option>
      <option value="draft" {{ $selected == 'draft' ? 'selected' : '' }}>Draft</option>
  </select>

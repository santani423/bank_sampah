  <label for="status_pengiriman" class="form-label">Status Pengiriman</label>
  <select class="form-control" name="{{ $name }}" id="{{ $name }}" aria-label="Default select example">
      <option value="">-- Pilih Status Pengiriman --</option>
      <option value="pending">Pending</option>
      <option value="dikirim">Dikirim</option>
      <option value="diterima">Diterima</option>
      <option value="batal">Batal</option>
      <option value="draft">Draft</option>
  </select>

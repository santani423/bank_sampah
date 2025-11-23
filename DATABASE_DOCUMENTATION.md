# Dokumentasi Database Bank Sampah

## Deskripsi
Dokumentasi ini berisi penjelasan struktur database, tabel utama, relasi, dan instruksi penggunaan diagram ERD/LRS.

---

## Diagram ERD & LRS
File diagram ERD dan LRS tersedia di:
- `erd_lrs_bank_sampah.drawio.xml`

Cara menggunakan:
1. Buka https://app.diagrams.net
2. Pilih File > Import From > Device
3. Pilih file `erd_lrs_bank_sampah.drawio.xml`
4. Diagram ERD dan LRS akan tampil dan bisa diedit sesuai kebutuhan

---

## Tabel Utama

### 1. nasabah
- id (PK)
- cabang_id (FK)
- no_registrasi
- nik
- nama_lengkap
- ...

### 2. saldo
- id (PK)
- nasabah_id (FK)
- saldo
- ...

### 3. petugas
- id (PK)
- nama
- email
- username
- ...

### 4. transaksi
- id (PK)
- kode_transaksi
- nasabah_id (FK)
- petugas_id (FK)
- ...

### 5. detail_transaksi
- id (PK)
- transaksi_id (FK)
- sampah_id (FK)
- berat_kg
- ...

### 6. sampah
- id (PK)
- nama_sampah
- harga_per_kg
- ...

### 7. cabangs
- id (PK)
- kode_cabang
- nama_cabang
- ...

### 8. lapak
- id (PK)
- cabang_id (FK)
- kode_lapak
- nama_lapak
- ...

---

## Relasi Utama
- nasabah → saldo (1-n)
- nasabah → transaksi (1-n)
- petugas → transaksi (1-n)
- transaksi → detail_transaksi (1-n)
- detail_transaksi → sampah (n-1)
- cabangs → lapak (1-n)

---

## Catatan
- Untuk detail field dan relasi lain, lihat file migrasi di folder `database/migrations`.
- Diagram dapat diperluas sesuai kebutuhan proyek.

---

## Kontak
Untuk pertanyaan lebih lanjut, hubungi admin proyek atau lihat file README.md.

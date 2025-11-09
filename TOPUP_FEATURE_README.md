# Fitur Top Up Saldo Utama dengan Xendit

## Deskripsi
Fitur ini memungkinkan admin untuk melakukan top up saldo utama Bank Sampah menggunakan payment gateway Xendit. Setiap transaksi top up akan tercatat dalam sistem dan saldo akan otomatis bertambah setelah pembayaran berhasil.

## Struktur Database

### 1. Tabel `saldo_utama`
Menyimpan saldo utama Bank Sampah

| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| saldo | decimal(15,2) | Total saldo utama |
| keterangan | text | Keterangan/catatan terakhir |
| created_at | timestamp | Waktu dibuat |
| updated_at | timestamp | Waktu diupdate |

### 2. Tabel `top_up_admin`
Menyimpan riwayat setiap kali admin melakukan top up

| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| user_id | bigint | ID admin yang melakukan top up |
| jumlah | decimal(15,2) | Jumlah top up |
| metode_pembayaran | string | Metode pembayaran (xendit) |
| status | string | Status: pending, success, failed, expired |
| xendit_invoice_id | string | Invoice ID dari Xendit |
| xendit_invoice_url | string | URL pembayaran Xendit |
| xendit_external_id | string | External ID transaksi |
| keterangan | text | Keterangan top up |
| tanggal_bayar | timestamp | Tanggal pembayaran berhasil |
| created_at | timestamp | Waktu dibuat |
| updated_at | timestamp | Waktu diupdate |

## Models

### SaldoUtama.php
```php
- Location: app/Models/SaldoUtama.php
- Fillable: saldo, keterangan
- Casts: saldo => decimal:2
```

### TopUpAdmin.php
```php
- Location: app/Models/TopUpAdmin.php
- Fillable: user_id, jumlah, metode_pembayaran, status, xendit_invoice_id, 
           xendit_invoice_url, xendit_external_id, keterangan, tanggal_bayar
- Casts: jumlah => decimal:2, tanggal_bayar => datetime
- Relations: belongsTo(User)
```

## Controller

### TopUpController.php
Location: `app/Http/Controllers/Admin/TopUpController.php`

**Methods:**
1. `index()` - Menampilkan halaman daftar top up dan saldo utama
2. `create()` - Menampilkan form top up
3. `store(Request $request)` - Membuat invoice Xendit dan menyimpan data top up
4. `callback(Request $request)` - Webhook untuk menerima notifikasi dari Xendit
5. `success()` - Halaman sukses setelah pembayaran
6. `show($id)` - Menampilkan detail top up

## Routes

### Admin Routes (web.php)
```php
// Top Up Saldo Utama
Route::get('/topup', [AdminTopUpController::class, 'index'])->name('admin.topup.index');
Route::get('/topup/create', [AdminTopUpController::class, 'create'])->name('admin.topup.create');
Route::post('/topup/store', [AdminTopUpController::class, 'store'])->name('admin.topup.store');
Route::get('/topup/success', [AdminTopUpController::class, 'success'])->name('admin.topup.success');
Route::get('/topup/{id}', [AdminTopUpController::class, 'show'])->name('admin.topup.show');

// Xendit Callback (tidak perlu auth)
Route::post('/xendit/callback/topup', [AdminTopUpController::class, 'callback'])->name('xendit.callback.topup');
```

## Views

### 1. index.blade.php
Location: `resources/views/pages/admin/topup/index.blade.php`
- Menampilkan saldo utama dalam card yang menarik
- Tabel riwayat top up dengan status dan tombol aksi
- Pagination untuk daftar top up

### 2. create.blade.php
Location: `resources/views/pages/admin/topup/create.blade.php`
- Form input jumlah top up
- Tombol nominal cepat (10rb, 50rb, 100rb, dst)
- Input keterangan (opsional)
- Validasi minimal Rp 10.000

### 3. success.blade.php
Location: `resources/views/pages/admin/topup/success.blade.php`
- Halaman konfirmasi setelah pembayaran
- Informasi bahwa pembayaran sedang diproses

### 4. show.blade.php
Location: `resources/views/pages/admin/topup/show.blade.php`
- Detail lengkap transaksi top up
- Status pembayaran
- Link pembayaran jika masih pending

## Menu Navigasi

Menu "Top Up Saldo Utama" ditambahkan di sidebar admin setelah menu Transaksi:
```blade
<li class="nav-item">
    <a href="{{ route('admin.topup.index') }}">
        <i class="fas fa-wallet"></i>
        <p>Top Up Saldo Utama</p>
    </a>
</li>
```

## Konfigurasi Xendit

### 1. Environment Variables
Pastikan file `.env` sudah dikonfigurasi dengan credential Xendit:

```env
XENDIT_SECRET_KEY=your_secret_key_here
XENDIT_PUBLIC_KEY=your_public_key_here
XENDIT_CALLBACK_TOKEN=your_callback_token_here
```

### 2. Xendit Configuration
File `config/xendit.php` harus berisi:
```php
return [
    'secret_key' => env('XENDIT_SECRET_KEY'),
    'public_key' => env('XENDIT_PUBLIC_KEY'),
    'callback_token' => env('XENDIT_CALLBACK_TOKEN'),
];
```

### 3. Webhook Xendit
Setelah deployment, set webhook URL di dashboard Xendit:
```
https://yourdomain.com/xendit/callback/topup
```

## Cara Penggunaan

### 1. Admin Melakukan Top Up
1. Login sebagai admin
2. Klik menu "Top Up Saldo Utama" di sidebar
3. Klik tombol "Top Up Saldo"
4. Pilih nominal atau masukkan jumlah manual (min. Rp 10.000)
5. Tambahkan keterangan (opsional)
6. Klik "Lanjutkan ke Pembayaran"
7. Akan diarahkan ke halaman pembayaran Xendit
8. Pilih metode pembayaran yang diinginkan
9. Selesaikan pembayaran

### 2. Setelah Pembayaran
- Setelah pembayaran berhasil, Xendit akan mengirim webhook ke sistem
- Sistem akan otomatis update status top up menjadi "success"
- Saldo utama akan bertambah sesuai jumlah top up
- Admin bisa cek status di halaman riwayat top up

### 3. Melihat Riwayat
- Halaman index menampilkan semua riwayat top up
- Status ditampilkan dengan badge berwarna:
  - **Success** (hijau): Pembayaran berhasil
  - **Pending** (kuning): Menunggu pembayaran
  - **Failed** (merah): Pembayaran gagal
  - **Expired** (abu-abu): Invoice kadaluarsa

## Status Pembayaran

1. **pending** - Invoice dibuat, menunggu pembayaran
2. **success** - Pembayaran berhasil, saldo sudah bertambah
3. **failed** - Pembayaran gagal
4. **expired** - Invoice kadaluarsa (24 jam)

## Fitur Keamanan

1. **Callback Verification** - Webhook dari Xendit diverifikasi menggunakan callback token
2. **Foreign Key** - Relasi user_id ke tabel users dengan cascade delete
3. **Transaction** - Menggunakan database transaction untuk konsistensi data
4. **Validation** - Input divalidasi sebelum diproses

## Testing

### 1. Test Manual
```bash
# 1. Buat top up
- Login sebagai admin
- Akses /admin/topup/create
- Isi form dan submit

# 2. Simulasi callback (development)
POST /xendit/callback/topup
Header: x-callback-token: YOUR_CALLBACK_TOKEN
Body: {
    "external_id": "TOPUP-xxx",
    "status": "PAID",
    ...
}
```

### 2. Test dengan Postman
Gunakan Xendit test API keys untuk testing tanpa pembayaran real.

## Troubleshooting

### Invoice tidak terbuat
- Cek koneksi internet
- Pastikan Xendit API key valid
- Cek log error di `storage/logs/laravel.log`

### Callback tidak diterima
- Pastikan webhook URL sudah diset di dashboard Xendit
- Pastikan callback token sama antara .env dan Xendit dashboard
- Cek firewall/server apakah memblok request dari Xendit

### Saldo tidak bertambah
- Cek status top up di database
- Pastikan callback berhasil (cek log)
- Pastikan status = 'success' dan tanggal_bayar terisi

## Maintenance

### Membersihkan invoice expired
```php
// Bisa dibuat scheduled command untuk auto-cleanup
TopUpAdmin::where('status', 'pending')
    ->where('created_at', '<', now()->subDay())
    ->update(['status' => 'expired']);
```

### Backup Data
Pastikan tabel `saldo_utama` dan `top_up_admin` ter-backup secara regular.

## Dependencies

- Laravel 10+
- Xendit PHP SDK
- MySQL/PostgreSQL

## Support

Untuk pertanyaan atau bug report, hubungi tim developer.

---
**Dibuat:** November 2025
**Versi:** 1.0.0

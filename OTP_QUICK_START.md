# Quick Start: Fitur OTP Registrasi

## Cara Menggunakan

### 1. Jalankan Migration
```bash
php artisan migrate
```

### 2. Pastikan WhatsApp Service Sudah Dikonfigurasi
- Cek tabel `token_whatsapp` sudah terisi dengan device ID yang valid
- Pastikan WHCenter service aktif

### 3. Testing Registrasi

1. **Akses halaman registrasi**
   ```
   http://your-domain/register
   ```

2. **Isi form registrasi**
   - Nama Lengkap
   - Jenis Kelamin
   - No HP (contoh: 08123456789)
   - Email
   - Username
   - Password & Konfirmasi Password
   - Alamat Lengkap
   - Pilih Cabang

3. **Klik tombol "Register"**
   - Modal konfirmasi akan muncul

4. **Klik "Kirim OTP"**
   - Sistem akan mengirim kode OTP 6 digit ke WhatsApp
   - Anda akan diarahkan ke halaman verifikasi OTP

5. **Masukkan kode OTP**
   - Input 6 digit kode yang diterima via WhatsApp
   - Klik "Verifikasi"

6. **Registrasi selesai**
   - Jika OTP benar, Anda akan diarahkan ke halaman login
   - Gunakan username dan password untuk login

## Fitur Tambahan

### Resend OTP
- Jika tidak menerima OTP, tunggu 60 detik
- Klik tombol "Kirim Ulang OTP"

### Format Nomor HP
- Nomor akan otomatis diformat ke format internasional
- 08123456789 → 628123456789

## Troubleshooting

### OTP tidak terkirim?
1. Cek WhatsApp service aktif
2. Cek token di tabel `token_whatsapp`
3. Cek nomor HP sudah benar

### OTP expired?
- OTP berlaku 5 menit
- Request OTP baru dengan klik "Kirim Ulang OTP"

### Session expired?
- Kembali ke halaman registrasi
- Isi ulang form

## File Penting

- Migration: `database/migrations/2026_01_17_160523_create_otp_verifications_table.php`
- Model: `app/Models/OtpVerification.php`
- Controller: `app/Http/Controllers/AuthController.php`
- View OTP: `resources/views/pages/auth/verify-otp.blade.php`
- View Register: `resources/views/pages/auth/register.blade.php`
- Service: `app/Services/WhatsAppService.php`

Untuk dokumentasi lengkap, lihat: [OTP_FEATURE_DOCUMENTATION.md](OTP_FEATURE_DOCUMENTATION.md)

# Dokumentasi Fitur OTP Registrasi via WhatsApp

## Overview
Fitur ini menambahkan verifikasi OTP (One Time Password) via WhatsApp pada proses registrasi user. User harus memasukkan kode OTP yang dikirimkan ke nomor WhatsApp mereka sebelum registrasi berhasil.

## Flow Registrasi dengan OTP

### 1. User Mengisi Form Registrasi
- User mengisi data lengkap: nama, jenis kelamin, no HP, email, username, password, alamat, dan cabang
- User klik tombol "Register"
- Muncul modal konfirmasi data

### 2. Pengiriman OTP
- User klik "Kirim OTP" pada modal konfirmasi
- Sistem memvalidasi data registrasi
- Sistem generate kode OTP 6 digit
- OTP disimpan ke database dengan masa berlaku 5 menit
- OTP dikirim ke nomor WhatsApp user melalui WhatsApp service
- Data registrasi disimpan di session
- User diarahkan ke halaman verifikasi OTP

### 3. Verifikasi OTP
- User memasukkan 6 digit kode OTP yang diterima via WhatsApp
- User klik "Verifikasi"
- Sistem memverifikasi kode OTP
- Jika valid, sistem melanjutkan proses registrasi:
  - Create data Nasabah
  - Create data User
  - Create data UserNasabah
  - Create data CabangUser
- Jika tidak valid, tampilkan error

### 4. Fitur Tambahan
- **Resend OTP**: User bisa kirim ulang OTP jika tidak menerima
- **Countdown Timer**: Resend OTP tersedia setelah 60 detik
- **Auto Focus**: Input OTP otomatis berpindah ke field berikutnya
- **Paste Support**: Support paste kode OTP 6 digit sekaligus

## File-File yang Dibuat/Dimodifikasi

### 1. Database Migration
**File**: `database/migrations/2026_01_17_160523_create_otp_verifications_table.php`
- Tabel untuk menyimpan OTP
- Fields: phone_number, otp_code, type, is_verified, expires_at

### 2. Model
**File**: `app/Models/OtpVerification.php`
- Model untuk OTP verification
- Methods:
  - `generateOTP()`: Generate dan simpan OTP baru
  - `verifyOTP()`: Verifikasi kode OTP
  - `isExpired()`: Cek apakah OTP expired

### 3. Service
**File**: `app/Services/WhatsAppService.php`
- Added method: `sendOTP()` untuk mengirim OTP via WhatsApp

### 4. Controller
**File**: `app/Http/Controllers/AuthController.php`
- Added methods:
  - `sendOTP()`: Handle pengiriman OTP
  - `showVerifyOTP()`: Tampilkan form verifikasi OTP
  - `verifyOTP()`: Handle verifikasi OTP
  - `resendOTP()`: Handle resend OTP

### 5. Views
**File**: `resources/views/pages/auth/verify-otp.blade.php`
- Halaman verifikasi OTP
- 6 input boxes untuk kode OTP
- Countdown timer untuk resend
- Auto focus antar input

**File**: `resources/views/pages/auth/register.blade.php`
- Updated: Form menggunakan AJAX untuk kirim OTP
- Modal button berubah dari "Konfirmasi & Daftar" menjadi "Kirim OTP"

### 6. Routes
**File**: `routes/web.php`
- Updated routes untuk OTP flow:
  ```php
  Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register.form');
  Route::post('register/send-otp', [AuthController::class, 'sendOTP'])->name('send.otp');
  Route::get('register/verify-otp', [AuthController::class, 'showVerifyOTP'])->name('verify.otp.form');
  Route::post('register/verify-otp', [AuthController::class, 'verifyOTP'])->name('verify.otp');
  Route::post('register/resend-otp', [AuthController::class, 'resendOTP'])->name('resend.otp');
  ```

## Konfigurasi WhatsApp

Pastikan konfigurasi WhatsApp service sudah benar di tabel `token_whatsapp`:
- Device ID dari WHCenter
- Token aktif dan valid

## Testing

### Test Flow Registrasi:
1. Buka halaman registrasi: `/register`
2. Isi semua field dengan data valid
3. Klik "Register" → Modal konfirmasi muncul
4. Klik "Kirim OTP" 
5. Cek WhatsApp untuk menerima kode OTP
6. Masukkan 6 digit kode OTP di halaman verifikasi
7. Klik "Verifikasi"
8. Jika sukses, redirect ke login page

### Test Resend OTP:
1. Di halaman verifikasi OTP
2. Tunggu countdown 60 detik selesai
3. Klik "Kirim Ulang OTP"
4. Cek WhatsApp untuk menerima kode OTP baru

### Test OTP Expired:
1. Tunggu lebih dari 5 menit setelah OTP dikirim
2. Masukkan kode OTP
3. Sistem akan menolak dengan pesan error

## Error Handling

### Validasi Error:
- Field kosong atau tidak valid
- Email/username sudah terdaftar
- Password tidak match

### OTP Error:
- Gagal mengirim OTP via WhatsApp
- Kode OTP salah
- Kode OTP expired (> 5 menit)
- Session expired

## Security Features

1. **OTP Expiration**: OTP berlaku hanya 5 menit
2. **One-time Use**: OTP hanya bisa digunakan sekali
3. **Session Management**: Data registrasi disimpan di session
4. **CSRF Protection**: Semua form dilindungi CSRF token
5. **Auto Delete**: OTP lama otomatis dihapus saat generate OTP baru

## Format Nomor Telepon

Sistem otomatis format nomor telepon:
- Input: `0812345678` → Format: `62812345678`
- Input: `812345678` → Format: `62812345678`
- Input: `62812345678` → Format: `62812345678`

## Database Schema

```sql
CREATE TABLE otp_verifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    phone_number VARCHAR(255) NOT NULL,
    otp_code VARCHAR(6) NOT NULL,
    type VARCHAR(255) DEFAULT 'registration',
    is_verified BOOLEAN DEFAULT FALSE,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_phone_otp (phone_number, otp_code)
);
```

## Troubleshooting

### OTP Tidak Terkirim
1. Cek koneksi WhatsApp service
2. Cek token WhatsApp di tabel `token_whatsapp`
3. Cek format nomor telepon

### Error Session
1. Pastikan session driver sudah dikonfigurasi
2. Clear cache: `php artisan cache:clear`
3. Clear session: `php artisan session:clear`

### Error Database
1. Pastikan migration sudah dijalankan: `php artisan migrate`
2. Cek koneksi database

## Next Steps / Improvements

1. **Rate Limiting**: Batasi jumlah request OTP per nomor
2. **Logging**: Tambahkan logging untuk tracking pengiriman OTP
3. **SMS Fallback**: Tambahkan opsi SMS jika WhatsApp gagal
4. **Email Verification**: Tambahkan verifikasi email
5. **Admin Panel**: Dashboard untuk monitoring OTP

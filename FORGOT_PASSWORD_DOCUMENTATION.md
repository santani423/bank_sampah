# Dokumentasi Fitur Forgot Password dengan OTP WhatsApp

## Overview
Fitur ini memungkinkan user untuk mereset password mereka dengan menggunakan verifikasi OTP yang dikirim melalui WhatsApp. User hanya perlu memasukkan nomor WhatsApp yang terdaftar untuk menerima kode OTP.

## Flow Forgot Password dengan OTP

### 1. User Mengakses Halaman Forgot Password
- User klik link "Forgot Password?" di halaman login
- User diarahkan ke halaman forgot password (`/forgot-password`)
- User memasukkan nomor WhatsApp yang terdaftar
- Format nomor: 628xxxxxxxxxx (dengan kode negara 62)

### 2. Pengiriman OTP
- User klik tombol "Kirim Kode OTP"
- Sistem memvalidasi:
  - Nomor WhatsApp wajib diisi
  - Nomor WhatsApp harus dimulai dengan kode negara 62
  - Nomor WhatsApp harus terdaftar di sistem
- Jika valid:
  - Sistem generate kode OTP 6 digit
  - OTP disimpan ke database dengan type `forgot_password`
  - OTP berlaku selama 5 menit
  - OTP dikirim ke nomor WhatsApp user
  - Nomor WhatsApp disimpan di session
  - User diarahkan ke halaman reset password

### 3. Reset Password
- User memasukkan:
  - 6 digit kode OTP yang diterima via WhatsApp
  - Password baru (minimal 6 karakter)
  - Konfirmasi password baru
- User klik "Reset Password"
- Sistem memverifikasi:
  - Kode OTP benar dan belum expired
  - Password minimal 6 karakter
  - Password dan konfirmasi password cocok
- Jika valid:
  - Password user diupdate di database
  - Session dibersihkan
  - User diarahkan ke halaman login
  - Tampil notifikasi sukses

### 4. Fitur Tambahan
- **Resend OTP**: User bisa kirim ulang OTP jika tidak menerima
- **Countdown Timer**: Resend OTP tersedia setelah 60 detik
- **Auto Focus**: Input OTP otomatis berpindah ke field berikutnya
- **Paste Support**: Support paste kode OTP 6 digit sekaligus
- **Password Toggle**: User bisa show/hide password saat input

## File-File yang Dibuat/Dimodifikasi

### 1. Routes (`routes/web.php`)
```php
// Forgot Password Routes
Route::get('forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot.password.form');
Route::post('forgot-password/send-otp', [AuthController::class, 'sendForgotPasswordOTP'])->name('forgot.password.send.otp');
Route::get('forgot-password/reset', [AuthController::class, 'showResetPasswordForm'])->name('forgot.password.reset.form');
Route::post('forgot-password/reset', [AuthController::class, 'resetPassword'])->name('forgot.password.reset');
Route::post('forgot-password/resend-otp', [AuthController::class, 'resendForgotPasswordOTP'])->name('forgot.password.resend.otp');
```

### 2. Controller (`app/Http/Controllers/AuthController.php`)
**Methods yang ditambahkan:**

#### `showForgotPasswordForm()`
- Menampilkan halaman input nomor WhatsApp
- Return: view `pages.auth.forgot-password`

#### `sendForgotPasswordOTP(Request $request)`
- Validasi nomor WhatsApp
- Cek apakah nomor terdaftar di database
- Generate dan kirim OTP via WhatsApp
- Simpan nomor di session
- Return: JSON response

#### `showResetPasswordForm()`
- Cek session nomor WhatsApp
- Menampilkan halaman reset password
- Return: view `pages.auth.reset-password`

#### `resetPassword(Request $request)`
- Validasi OTP, password, dan konfirmasi password
- Verifikasi OTP dari database
- Update password user
- Clear session
- Redirect ke login

#### `resendForgotPasswordOTP(Request $request)`
- Generate OTP baru
- Kirim ke WhatsApp
- Return: JSON response

### 3. Views

#### `resources/views/pages/auth/forgot-password.blade.php`
- Form input nomor WhatsApp
- AJAX untuk kirim OTP tanpa reload page
- Validasi format nomor WhatsApp
- Loading overlay saat proses
- SweetAlert untuk notifikasi
- Link kembali ke login

#### `resources/views/pages/auth/reset-password.blade.php`
- Input OTP 6 digit dengan auto-focus
- Input password baru
- Input konfirmasi password
- Toggle show/hide password
- Resend OTP dengan countdown timer
- Form validation
- SweetAlert untuk notifikasi
- Link kembali ke login

#### `resources/views/pages/auth/login.blade.php`
- Update link "Forgot Password?" ke route `forgot.password.form`

### 4. Service (`app/Services/WhatsAppService.php`)
**Method yang ditambahkan:**

#### `sendForgotPasswordOTP(string $phone, string $otpCode)`
- Kirim OTP khusus untuk reset password
- Pesan berbeda dengan OTP registrasi
- Include warning jika user tidak merasa melakukan reset
- Return: array dengan status dan data

### 5. Model (`app/Models/OtpVerification.php`)
**Menggunakan model yang sudah ada dengan type berbeda:**
- Type: `forgot_password` (berbeda dengan `registration`)
- Method `generateOTP()` support multiple type
- Method `verifyOTP()` support multiple type

## Keamanan

### 1. Validasi Input
- Nomor WhatsApp wajib diisi dan format valid
- Nomor WhatsApp harus terdaftar di sistem
- OTP harus 6 digit angka
- Password minimal 6 karakter
- Password dan konfirmasi password harus cocok

### 2. OTP Security
- OTP berlaku hanya 5 menit
- OTP di-hash di database
- OTP hanya bisa digunakan sekali (flag `is_verified`)
- OTP lama dihapus saat generate OTP baru
- Type OTP berbeda untuk forgot password dan registrasi

### 3. Session Management
- Nomor WhatsApp disimpan di session (bukan di URL)
- Session dibersihkan setelah reset berhasil
- Session expired akan redirect ke form awal

### 4. Rate Limiting
- Resend OTP hanya bisa dilakukan setelah 60 detik
- Prevent spam OTP request

## Testing

### Test Case 1: Nomor WhatsApp Tidak Terdaftar
1. Buka `/forgot-password`
2. Input nomor WhatsApp yang tidak terdaftar
3. Klik "Kirim Kode OTP"
4. Expected: Error "Nomor WhatsApp tidak terdaftar"

### Test Case 2: Format Nomor Salah
1. Buka `/forgot-password`
2. Input nomor tanpa kode negara (contoh: 08123456789)
3. Klik "Kirim Kode OTP"
4. Expected: Error "Nomor WhatsApp harus dimulai dengan kode negara 62"

### Test Case 3: OTP Berhasil Dikirim
1. Buka `/forgot-password`
2. Input nomor WhatsApp yang terdaftar (contoh: 628123456789)
3. Klik "Kirim Kode OTP"
4. Expected: 
   - Sukses message
   - OTP dikirim ke WhatsApp
   - Redirect ke halaman reset password

### Test Case 4: OTP Salah
1. Lanjut dari Test Case 3
2. Input OTP yang salah
3. Input password baru
4. Klik "Reset Password"
5. Expected: Error "Kode OTP salah atau sudah kadaluarsa"

### Test Case 5: OTP Expired
1. Lanjut dari Test Case 3
2. Tunggu lebih dari 5 menit
3. Input OTP yang benar
4. Input password baru
5. Klik "Reset Password"
6. Expected: Error "Kode OTP salah atau sudah kadaluarsa"

### Test Case 6: Password Tidak Cocok
1. Lanjut dari Test Case 3
2. Input OTP yang benar
3. Input password baru: "password123"
4. Input konfirmasi password: "password456"
5. Klik "Reset Password"
6. Expected: Error "Konfirmasi password tidak cocok"

### Test Case 7: Reset Password Berhasil
1. Lanjut dari Test Case 3
2. Input OTP yang benar
3. Input password baru: "newpassword123"
4. Input konfirmasi password: "newpassword123"
5. Klik "Reset Password"
6. Expected:
   - Sukses message
   - Redirect ke login
   - Bisa login dengan password baru

### Test Case 8: Resend OTP
1. Di halaman reset password
2. Klik "Kirim Ulang OTP"
3. Tunggu countdown 60 detik selesai
4. Klik lagi "Kirim Ulang OTP"
5. Expected:
   - OTP baru dikirim
   - Countdown dimulai lagi
   - OTP lama tidak valid lagi

## Troubleshooting

### Problem: OTP tidak terkirim ke WhatsApp
**Solusi:**
1. Cek koneksi internet server
2. Cek token WhatsApp di `token_whatsapps` table
3. Cek log error di `storage/logs/laravel.log`
4. Pastikan WhatsApp service (WHCenter) aktif

### Problem: Session expired terus
**Solusi:**
1. Cek konfigurasi session di `.env`
2. Pastikan `SESSION_DRIVER` tidak error
3. Clear session: `php artisan session:clear`

### Problem: Password tidak terupdate
**Solusi:**
1. Cek relasi User - UserNasabah - Nasabah
2. Pastikan data lengkap di ketiga tabel
3. Cek log database transaction

## API Endpoints

### POST `/forgot-password/send-otp`
**Request:**
```json
{
    "no_hp": "628123456789"
}
```

**Response Success:**
```json
{
    "success": true,
    "message": "Kode OTP telah dikirim ke WhatsApp Anda",
    "phone": "628123456789"
}
```

**Response Error:**
```json
{
    "success": false,
    "message": "Nomor WhatsApp tidak terdaftar"
}
```

### POST `/forgot-password/reset`
**Request:**
```json
{
    "otp_code": "123456",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

**Response:** Redirect to login

### POST `/forgot-password/resend-otp`
**Response Success:**
```json
{
    "success": true,
    "message": "Kode OTP baru telah dikirim"
}
```

## Kustomisasi

### Mengubah Masa Berlaku OTP
Edit di `app/Models/OtpVerification.php`:
```php
'expires_at' => Carbon::now()->addMinutes(10) // Ubah dari 5 ke 10 menit
```

### Mengubah Pesan WhatsApp
Edit di `app/Services/WhatsAppService.php` method `sendForgotPasswordOTP()`:
```php
$message = "Custom message dengan kode OTP: *{$otpCode}*\n";
```

### Mengubah Countdown Timer
Edit di `resources/views/pages/auth/reset-password.blade.php`:
```javascript
let countdown = 120; // Ubah dari 60 ke 120 detik
```

## Dependencies
- Laravel 8+
- jQuery 3.6.0
- SweetAlert2
- WhatsApp Service (WHCenter)
- Bootstrap 5
- Phosphor Icons

## Related Documentation
- [OTP_FEATURE_DOCUMENTATION.md](OTP_FEATURE_DOCUMENTATION.md) - Dokumentasi fitur OTP registrasi
- [OTP_QUICK_START.md](OTP_QUICK_START.md) - Quick start guide OTP

# Quick Start - Fitur Forgot Password

## 📌 Cara Penggunaan

### Sebagai User:

1. **Buka halaman login** (`/login`)
2. **Klik "Forgot Password?"**
3. **Masukkan nomor WhatsApp** yang terdaftar (format: 628xxxxxxxxxx)
4. **Klik "Kirim Kode OTP"**
5. **Cek WhatsApp** untuk menerima kode OTP 6 digit
6. **Masukkan kode OTP** di halaman reset password
7. **Masukkan password baru** (minimal 6 karakter)
8. **Konfirmasi password** 
9. **Klik "Reset Password"**
10. **Login** dengan password baru

## 🔄 Flow Diagram

```
┌─────────────────┐
│  Halaman Login  │
└────────┬────────┘
         │
         │ Klik "Forgot Password?"
         ▼
┌─────────────────────────┐
│  Form Input No. WA      │
│  /forgot-password       │
└────────┬────────────────┘
         │
         │ Submit No. WA
         ▼
┌─────────────────────────┐
│  Kirim OTP ke WhatsApp  │
│  (berlaku 5 menit)      │
└────────┬────────────────┘
         │
         │ Auto redirect
         ▼
┌─────────────────────────┐
│  Form Reset Password    │
│  - Input OTP 6 digit    │
│  - Input Password Baru  │
│  - Konfirmasi Password  │
│  /forgot-password/reset │
└────────┬────────────────┘
         │
         │ Submit
         ▼
┌─────────────────────────┐
│  Password Berhasil      │
│  Diubah                 │
└────────┬────────────────┘
         │
         │ Redirect
         ▼
┌─────────────────┐
│  Halaman Login  │
│  (gunakan       │
│  password baru) │
└─────────────────┘
```

## 🚀 Testing

### Test Cepat:

1. **Pastikan database sudah ada data nasabah** dengan nomor WhatsApp
   ```sql
   SELECT * FROM nasabah WHERE no_hp IS NOT NULL;
   ```

2. **Pastikan WhatsApp service aktif** (cek tabel `token_whatsapps`)

3. **Akses** http://localhost:8000/forgot-password

4. **Input nomor WhatsApp** yang ada di database (contoh: 628123456789)

5. **Cek WhatsApp** untuk OTP

6. **Reset password** dan coba login

## ⚙️ Konfigurasi

### 1. Environment (.env)
Pastikan konfigurasi session sudah benar:
```env
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

### 2. Database
Tabel yang digunakan:
- `nasabah` - untuk cek nomor WhatsApp terdaftar
- `users` - untuk update password
- `user_nasabahs` - relasi user dan nasabah
- `otp_verifications` - untuk simpan dan verifikasi OTP
- `token_whatsapps` - untuk WhatsApp service token

### 3. WhatsApp Service
Pastikan token WhatsApp sudah diinput di admin panel:
- Login sebagai admin
- Buka menu Token WhatsApp
- Masukkan device_id dari WHCenter

## 📝 Routes

| Method | URL | Name | Deskripsi |
|--------|-----|------|-----------|
| GET | /forgot-password | forgot.password.form | Form input no. WA |
| POST | /forgot-password/send-otp | forgot.password.send.otp | Kirim OTP ke WA |
| GET | /forgot-password/reset | forgot.password.reset.form | Form reset password |
| POST | /forgot-password/reset | forgot.password.reset | Submit reset password |
| POST | /forgot-password/resend-otp | forgot.password.resend.otp | Kirim ulang OTP |

## 🛠️ Troubleshooting

### OTP tidak terkirim?
1. Cek koneksi internet server
2. Cek token WhatsApp di database
3. Cek log: `tail -f storage/logs/laravel.log`

### Nomor tidak terdaftar?
1. Pastikan format: 628xxxxxxxxxx (dengan kode negara)
2. Cek data di tabel nasabah:
   ```sql
   SELECT no_hp FROM nasabah WHERE no_hp LIKE '628%';
   ```

### Session expired?
1. Clear cache: `php artisan cache:clear`
2. Clear session: `php artisan session:clear`

### Password tidak terupdate?
1. Cek relasi user_nasabahs:
   ```sql
   SELECT * FROM user_nasabahs 
   JOIN users ON users.id = user_nasabahs.user_id
   JOIN nasabah ON nasabah.id = user_nasabahs.nasabah_id;
   ```

## 📚 Dokumentasi Lengkap

Lihat [FORGOT_PASSWORD_DOCUMENTATION.md](FORGOT_PASSWORD_DOCUMENTATION.md) untuk dokumentasi lengkap.

## 🔐 Keamanan

- ✅ OTP berlaku hanya 5 menit
- ✅ OTP hanya bisa digunakan 1 kali
- ✅ Nomor WhatsApp harus terdaftar
- ✅ Password minimal 6 karakter
- ✅ Session management yang aman
- ✅ Rate limiting untuk resend OTP (60 detik)

## 💡 Tips

1. **Format Nomor WhatsApp**: Selalu gunakan kode negara 62 (contoh: 628123456789)
2. **OTP Expired**: Gunakan fitur "Kirim Ulang OTP" jika OTP sudah expired
3. **Password Aman**: Gunakan kombinasi huruf dan angka untuk password
4. **Auto-Focus OTP**: Input OTP akan otomatis pindah ke kolom berikutnya
5. **Paste OTP**: Bisa langsung paste kode OTP 6 digit sekaligus

---

**Dibuat oleh**: GitHub Copilot  
**Tanggal**: 18 Januari 2026  
**Versi**: 1.0.0

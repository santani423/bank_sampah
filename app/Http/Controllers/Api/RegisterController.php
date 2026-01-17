<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\cabang;
use App\Models\CabangUser;
use App\Models\Nasabah;
use App\Models\Otp;
use App\Models\User;
use App\Models\UserNasabah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Services\WhatsAppService; // ✅ Tambahkan ini
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    protected $whatsappService;

    // ✅ Injeksi service WhatsApp melalui konstruktor
    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }
    /**
     * Get list cabang
     */
    public function getCabang()
    {
        try {
            $cabangs = cabang::select('id', 'nama_cabang')->get();

            return response()->json([
                'success' => true,
                'message' => 'Data cabang berhasil diambil',
                'data' => $cabangs
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data cabang',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Register new nasabah
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cabang_id'   => 'required|exists:cabangs,id',
            'nama_lengkap'   => 'required|string|max:255',
            'jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
            'no_hp'          => 'required|string|max:20',
            'email'          => 'required|email|unique:nasabah,email|unique:users,email',
            'username'       => 'required|string|unique:nasabah,username|unique:users,username|max:255',
            'password'       => 'required|string|confirmed|min:6',
            'alamat_lengkap' => 'required|string',
        ], [
            'cabang_id.required' => 'Cabang harus dipilih',
            'cabang_id.exists' => 'Cabang tidak valid',
            'nama_lengkap.required' => 'Nama lengkap harus diisi',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid',
            'no_hp.required' => 'No HP harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'username.required' => 'Username harus diisi',
            'username.unique' => 'Username sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.confirmed' => 'Konfirmasi password tidak sesuai',
            'password.min' => 'Password minimal 6 karakter',
            'alamat_lengkap.required' => 'Alamat lengkap harus diisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Create Nasabah
            $nasabah = new Nasabah();
            $nasabah->no_registrasi = 'REG-' . strtoupper(uniqid());
            $nasabah->nama_lengkap = $request->nama_lengkap;
            $nasabah->jenis_kelamin = $request->jenis_kelamin;
            $nasabah->no_hp = $request->no_hp;
            $nasabah->email = $request->email;
            $nasabah->username = $request->username;
            $nasabah->password = bcrypt($request->password);
            $nasabah->alamat_lengkap = $request->alamat_lengkap;
            $nasabah->status = 'aktif-face';
            $nasabah->save();

            // Create User
            $user = new User();
            $user->name = $nasabah->nama_lengkap;
            $user->username = $nasabah->username;
            $user->email = $nasabah->email;
            $user->password = bcrypt($request->password);
            $user->role = 'nasabah';
            $user->save();

            // Create UserNasabah
            $userNasabah = new UserNasabah();
            $userNasabah->user_id = $user->id;
            $userNasabah->nasabah_id = $nasabah->id;
            $userNasabah->save();

            // Create CabangUser
            $cabangUser = new CabangUser();
            $cabangUser->cabang_id = $request->cabang_id;
            $cabangUser->user_nasabah_id = $userNasabah->id;
            $cabangUser->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil! Silakan login.',
                'data' => [
                    'no_registrasi' => $nasabah->no_registrasi,
                    'nama_lengkap' => $nasabah->nama_lengkap,
                    'username' => $nasabah->username,
                    'email' => $nasabah->email,
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Registrasi gagal. Silakan coba lagi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function sendOTP(Request $request)
    {
        /**
         * ================================
         * 1. VALIDASI (AKTIFKAN SAAT PROD)
         * ================================
         */
    // $request->validate([
    //     'no_hp' => 'required|string'
    // ]);

        /**
         * ================================
         * 2. TENTUKAN NOMOR TUJUAN
         * ================================
         */
        // $no_hp = $request->no_hp; // PRODUKSI
        $no_hp = '088289445437';     // TESTING

        /**
         * ================================
         * 3. GENERATE OTP (AMAN)
         * ================================
         */
        $otpData = Otp::generate($no_hp, 'verification', 5);
        $otp = $otpData['otp'];

        /**
         * ================================
         * 4. SIMPAN OTP KE DATABASE
         * ================================
         */
        Otp::create([
            'identifier' => $no_hp,
            'otp_hash'   => Hash::make($otp),
            'type'       => 'verification',
            'expired_at' => now()->addMinutes(5),
            'is_used'    => false,
        ]);

        /**
         * ================================
         * 5. FORMAT PESAN WHATSAPP
         * ================================
         */
        $appName = config('app.name');

        $pesanAdmin = "🔐 *{$appName} – Verifikasi Akun*\n\n"
            . "Kode OTP Anda: *{$otp}*\n\n"
            . "Kode ini berlaku selama *5 menit*.\n"
            . "Demi keamanan akun Anda, jangan bagikan kode ini kepada siapa pun.\n\n"
            . "Jika Anda tidak merasa melakukan permintaan ini, silakan abaikan pesan ini.";

        try {
            /**
             * ================================
             * 6. KIRIM WHATSAPP
             * ================================
             */
            $this->whatsappService->sendMessage($no_hp, $pesanAdmin);

            return response()->json([
                'success' => true,
                'message' => 'OTP berhasil dikirim via WhatsApp'
            ]);
        } catch (\Throwable $e) {

            /**
             * ================================
             * 7. LOG ERROR
             * ================================
             */
            Log::error('Gagal kirim OTP WhatsApp', [
                'no_hp' => $no_hp,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim OTP'
            ], 500);
        }
    }
}

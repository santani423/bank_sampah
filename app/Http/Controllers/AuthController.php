<?php

namespace App\Http\Controllers;

use App\Models\cabang;
use App\Models\CabangUser;
use App\Models\Nasabah;
use App\Models\Setting;
use App\Models\User;
use App\Models\OtpVerification;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $whatsappService;

    // ✅ Injeksi service WhatsApp melalui konstruktor
    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }
    public function showLoginForm()
    {
        $setting  =  Setting::first();
        return view('pages.auth.login', compact('setting'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        // dd($request->all());
        // dd((Auth::attempt(['username' => $request->username, 'password' => $request->password]) ? 'Login Berhasil' : 'Login Gagal'));
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'petugas') {
                return redirect()->route('petugas.dashboard');
            } elseif ($user->role === 'nasabah' || $user->role === 'rekanan') {
                return redirect()->route('nasabah.dashboard');
            } else {

                return redirect()->route('login')->with('error', 'Role tidak dikenali');
            }
        }


        return back()->with('error', 'Username atau password salah.');
    }

    public function logout()
    {
        Auth::logout();

        Alert::success('Selamat Tinggal!', 'Anda telah berhasil logout.');

        return redirect()->route('login');
    }

    public function showRegistrationForm()
    {
        $cabangs = cabang::all();
        $setting  =  setting::first();
        return view('pages.auth.register', compact('cabangs', 'setting'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'cabang_id'   => 'required|string',
            'nama_lengkap'   => 'required|string',
            'jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
            // 'tempagital_lahir'  => 'required|date',
            'no_hp'          => 'required|string',
            'email'          => 'required|email|unique:nasabah,email',
            'username'       => 'required|string|unique:nasabah,username',
            'password'       => 'required|string|confirmed|min:6',
            'alamat_lengkap' => 'required|string',
        ]);

        $data = $request->except(['password', 'password_confirmation']);
        $data['password'] = bcrypt($request->password);



        $data['status'] = 'aktif';

        $nasabah  =    new  Nasabah();
        $nasabah->no_registrasi   = 'REG-' . strtoupper(uniqid());
        // $nasabah->nik = '-';
        $nasabah->status = 'aktif-face';
        $nasabah->fill($data);
        $nasabah->save();

        $user = new User();
        $user->name = $nasabah->nama_lengkap;
        $user->username = $nasabah->username;
        $user->email = $nasabah->email;
        $user->password = bcrypt($request->password);
        $user->role = 'nasabah';
        $user->save();

        $userNasabah = new \App\Models\UserNasabah();
        $userNasabah->user_id = $user->id;
        $userNasabah->nasabah_id = $nasabah->id;
        $userNasabah->save();

        $cabangUser  =  new CabangUser();
        $cabangUser->cabang_id = $request->cabang_id;
        $cabangUser->user_nasabah_id = $userNasabah->id;
        $cabangUser->save();

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    /**
     * Send OTP to phone number
     */
    public function sendOTP(Request $request)
    {
        try {
            /**
             * =================================
             * 1. VALIDASI (TIDAK REDIRECT)
             * =================================
             */
            $validator = Validator::make($request->all(), [
                'no_hp'           => 'required|string',
                'cabang_id'       => 'required|string',
                'nama_lengkap'    => 'required|string',
                'jenis_kelamin'   => 'required|in:Laki-laki,Perempuan',
                'email'           => 'required|email|unique:nasabah,email',
                'username'        => 'required|string|unique:nasabah,username',
                'password'        => 'required|string|confirmed|min:6',
                'alamat_lengkap'  => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'type'    => 'validation_error',
                    'message' => 'Validasi gagal',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            /**
             * =================================
             * 2. FORMAT NOMOR HP (62)
             * =================================
             */
            $phoneNumber = $request->no_hp;

            if (str_starts_with($phoneNumber, '0')) {
                $phoneNumber = '62' . substr($phoneNumber, 1);
            } elseif (!str_starts_with($phoneNumber, '62')) {
                $phoneNumber = '62' . $phoneNumber;
            }

            /**
             * =================================
             * 3. GENERATE OTP (DB)
             * =================================
             */
            $otp = OtpVerification::generateOTP($phoneNumber, 'registration');

            /**
             * =================================
             * 4. KIRIM OTP VIA WHATSAPP
             * =================================
             */
            $whatsappService = new WhatsAppService();
            $result = $whatsappService->sendOTP($phoneNumber, $otp->otp_code);

            if (!$result['status']) {
                return response()->json([
                    'success' => false,
                    'type'    => 'whatsapp_failed',
                    'message' => 'Gagal mengirim OTP. Silakan coba lagi.',
                ], 500);
            }

            /**
             * =================================
             * 5. SIMPAN DATA REGISTRASI KE SESSION
             * =================================
             */
            Session::put('registration_data', $request->except([
                'password_confirmation'
            ]));

            Session::put('otp_phone', $phoneNumber);

            /**
             * =================================
             * 6. RESPONSE SUKSES
             * =================================
             */
            return response()->json([
                'success' => true,
                'message' => 'Kode OTP telah dikirim ke WhatsApp Anda',
                'phone'   => $phoneNumber,
            ]);
        } catch (\Throwable $e) {

            /**
             * =================================
             * 7. ERROR GLOBAL (PASTI JSON)
             * =================================
             */
            return response()->json([
                'success' => false,
                'type'    => 'server_error',
                'message' => 'Terjadi kesalahan pada server',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Show OTP verification form
     */
    public function showVerifyOTP()
    {
        if (!Session::has('registration_data')) {
            return redirect()->route('register.form')->with('error', 'Silakan isi form registrasi terlebih dahulu');
        }

        $setting = Setting::first();
        $phoneNumber = Session::get('otp_phone');

        return view('pages.auth.verify-otp', compact('setting', 'phoneNumber'));
    }

    /**
     * Verify OTP and complete registration
     */
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|string|size:6'
        ]);

        $phoneNumber = Session::get('otp_phone');
        $registrationData = Session::get('registration_data');

        if (!$phoneNumber || !$registrationData) {
            return back()->with('error', 'Sesi registrasi telah berakhir. Silakan daftar ulang.');
        }

        // Verify OTP
        if (OtpVerification::verifyOTP($phoneNumber, $request->otp_code, 'registration')) {
            try {
                DB::beginTransaction();

                // Create Nasabah
                $data = $registrationData;
                $data['password'] = bcrypt($data['password']);
                $data['status'] = 'aktif';

                $nasabah = new Nasabah();
                $nasabah->no_registrasi = 'REG-' . strtoupper(uniqid());
                $nasabah->status = 'aktif';
                $nasabah->fill($data);
                $nasabah->save();

                // Create User
                $user = new User();
                $user->name = $nasabah->nama_lengkap;
                $user->username = $nasabah->username;
                $user->email = $nasabah->email;
                $user->password = bcrypt($registrationData['password']);
                $user->role = 'nasabah';
                $user->save();

                // Create UserNasabah
                $userNasabah = new \App\Models\UserNasabah();
                $userNasabah->user_id = $user->id;
                $userNasabah->nasabah_id = $nasabah->id;
                $userNasabah->save();

                // Create CabangUser
                $cabangUser = new CabangUser();
                $cabangUser->cabang_id = $registrationData['cabang_id'];
                $cabangUser->user_nasabah_id = $userNasabah->id;
                $cabangUser->save();

                DB::commit();

                // Clear session
                Session::forget(['registration_data', 'otp_phone']);

                Alert::success('Berhasil!', 'Registrasi berhasil! Silakan login.');
                return redirect()->route('login');
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Terjadi kesalahan saat registrasi: ' . $e->getMessage());
            }
        } else {
            return back()->with('error', 'Kode OTP salah atau sudah kadaluarsa. Silakan coba lagi.');
        }
    }

    /**
     * Resend OTP
     */
    public function resendOTP(Request $request)
    {
        $phoneNumber = Session::get('otp_phone');

        if (!$phoneNumber) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi telah berakhir'
            ], 400);
        }

        try {
            // Generate new OTP
            $otp = OtpVerification::generateOTP($phoneNumber, 'registration');

            // Send OTP via WhatsApp
            $whatsappService = new WhatsAppService();
            $result = $whatsappService->sendOTP($phoneNumber, $otp->otp_code);

            if ($result['status']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kode OTP baru telah dikirim'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim OTP'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show Forgot Password Form
     */
    public function showForgotPasswordForm()
    {
        $setting = Setting::first();
        return view('pages.auth.forgot-password', compact('setting'));
    }

    /**
     * Send OTP for forgot password
     */
    public function sendForgotPasswordOTP(Request $request)
    {
        try {
            // 1. Validasi input
            $validator = Validator::make($request->all(), [
                'no_hp' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor WhatsApp wajib diisi',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            $phoneNumber = $request->no_hp;

            // 2. Cek apakah nomor terdaftar
            $nasabah = Nasabah::where('no_hp', $phoneNumber)->first();

            if (!$nasabah) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor WhatsApp tidak terdaftar',
                ], 404);
            }

            // 3. Generate OTP
            $otp = OtpVerification::generateOTP($phoneNumber, 'forgot_password');

            // 4. Susun pesan WhatsApp OTP
            $message = "Halo {$nasabah->nama},\n\n"
                . "Kode OTP Anda untuk proses *Reset Password* adalah:\n\n"
                . "🔐 {$otp->otp_code}\n\n"
                . "Kode ini berlaku selama 5 menit.\n"
                . "JANGAN bagikan kode ini kepada siapa pun, termasuk pihak yang mengatasnamakan kami.\n\n"
                . "Jika Anda tidak merasa meminta reset password, silakan abaikan pesan ini.\n\n"
                . "Terima kasih.";

            // 5. Kirim OTP via WhatsApp
            $this->whatsappService->sendMessage($phoneNumber, $message);

            // 6. Simpan nomor ke session (untuk verifikasi selanjutnya)
            Session::put('forgot_password_phone', $phoneNumber);

            // 7. Response sukses
            return response()->json([
                'success' => true,
                'message' => 'Kode OTP telah dikirim ke WhatsApp Anda',
                'phone'   => $phoneNumber,
            ], 200);
        } catch (\Throwable $e) {

            // 8. Error handling
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
    /**
     * Show Reset Password Form
     */
    public function showResetPasswordForm()
    {
        if (!Session::has('forgot_password_phone')) {
            return redirect()->route('forgot.password.form')->with('error', 'Silakan masukkan nomor WhatsApp terlebih dahulu');
        }

        $setting = Setting::first();
        $phoneNumber = Session::get('forgot_password_phone');

        return view('pages.auth.reset-password', compact('setting', 'phoneNumber'));
    }

    /**
     * Reset Password with OTP verification
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|string|size:6',
            'password' => 'required|string|confirmed|min:6',
        ]);

        $phoneNumber = Session::get('forgot_password_phone');

        if (!$phoneNumber) {
            return back()->with('error', 'Sesi telah berakhir. Silakan ulangi proses reset password.');
        }

        // Verify OTP
        if (OtpVerification::verifyOTP($phoneNumber, $request->otp_code, 'forgot_password')) {
            try {
                DB::beginTransaction();

                // Find nasabah by phone number
                $nasabah = Nasabah::where('no_hp', $phoneNumber)->first();

                if (!$nasabah) {
                    return back()->with('error', 'User tidak ditemukan.');
                }

                // Update password in User table
                $userNasabah = \App\Models\UserNasabah::where('nasabah_id', $nasabah->id)->first();
                if ($userNasabah) {
                    $user = User::find($userNasabah->user_id);
                    if ($user) {
                        $user->password = bcrypt($request->password);
                        $user->save();
                    }
                }

                DB::commit();

                // Clear session
                Session::forget('forgot_password_phone');

                Alert::success('Berhasil!', 'Password berhasil diubah! Silakan login dengan password baru.');
                return redirect()->route('login');
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Terjadi kesalahan saat mengubah password: ' . $e->getMessage());
            }
        } else {
            return back()->with('error', 'Kode OTP salah atau sudah kadaluarsa. Silakan coba lagi.');
        }
    }

    /**
     * Resend OTP for forgot password
     */
    public function resendForgotPasswordOTP(Request $request)
    {
        $phoneNumber = Session::get('forgot_password_phone');

        if (!$phoneNumber) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi telah berakhir'
            ], 400);
        }

        try {
            // Generate new OTP
            $otp = OtpVerification::generateOTP($phoneNumber, 'forgot_password');

            // Send OTP via WhatsApp
            $whatsappService = new WhatsAppService();
            $result = $whatsappService->sendOTP($phoneNumber, $otp->otp_code);

            if ($result['status']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kode OTP baru telah dikirim'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim OTP'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}

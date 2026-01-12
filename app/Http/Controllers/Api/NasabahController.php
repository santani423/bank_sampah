<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nasabah;
use App\Models\PencairanSaldo;
use App\Models\Petugas;
use App\Models\Saldo;
use App\Models\Setting;
use App\Models\UserNasabah;
use Illuminate\Support\Facades\DB;
use App\Services\WhatsAppService; // ✅ Tambahkan ini
use Illuminate\Support\Facades\Validator;

class NasabahController extends Controller
{
    protected $whatsappService;

    // ✅ Injeksi service WhatsApp melalui konstruktor
    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Handle listing nasabah (perorangan) with pagination & search.
     */
    public function index(Request $request)
    {
        try {
            //
            $authUser = auth()->user();

            // Cari petugas berdasarkan email user login
            $petugas = Petugas::where('email', $authUser->email)->first();

            // =========================
            // 1. QUERY DASAR
            // =========================
            $query = Nasabah::query()
                ->select([
                    'nasabah.*',
                    'cabangs.nama_cabang as nama_cabang', // TAMBAHAN
                ])
                ->with(['saldo'])
                ->join('user_nasabahs', 'nasabah.id', '=', 'user_nasabahs.nasabah_id')
                ->join('cabang_users', 'user_nasabahs.id', '=', 'cabang_users.user_nasabah_id')
                ->join('cabangs', 'cabang_users.cabang_id', '=', 'cabangs.id');

            // Jika petugas ditemukan, filter berdasarkan petugas
            if ($petugas) {
                $query->join('petugas_cabangs', 'cabangs.id', '=', 'petugas_cabangs.cabang_id')
                    ->where('petugas_cabangs.petugas_id', $petugas->id);
            }
            // =========================
            // 2. FILTER SEARCH
            // =========================
            $query->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;

                $q->where(function ($sub) use ($search) {
                    $sub->where('nasabah.nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('nasabah.no_registrasi', 'like', "%{$search}%")
                        ->orWhere('user_nasabahs.username', 'like', "%{$search}%");
                });
            });

            // =========================
            // 3. FILTER BY KODE CABANG (BARU)
            // =========================
            $query->when($request->filled('cabang'), function ($q) use ($request) {
                $q->where('cabangs.kode_cabang', $request->cabang);
            });

            // =========================
            // 4. VALIDASI PER PAGE
            // =========================
            $perPage = (int) $request->get('per_page', 10);
            $perPage = ($perPage > 0 && $perPage <= 100) ? $perPage : 10;

            // =========================
            // 5. PAGINATION
            // =========================
            $nasabahs = $query
                ->orderByDesc('nasabah.id')
                ->paginate($perPage);

            // =========================
            // 6. RESPONSE SUKSES
            // =========================
            return response()->json([
                'success' => true,
                'message' => 'Data nasabah berhasil diambil.',
                'data'    => $nasabahs->items(),
                'pagination' => [
                    'current_page' => $nasabahs->currentPage(),
                    'last_page'    => $nasabahs->lastPage(),
                    'per_page'     => $nasabahs->perPage(),
                    'total'        => $nasabahs->total(),
                    'from'         => $nasabahs->firstItem(),
                    'to'           => $nasabahs->lastItem(),
                ],
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data nasabah.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }


    /**
     * Daftar nasabah berdasarkan petugas (perorangan) dengan pagination & optional search.
     * Route: GET /api/nasabah
     * Query params: email, search, page, per_page
     */

    public function nasabahPetugas(Request $request)
    {
        // Support passing petugas email explicitly via query, fallback to authenticated user
        $user = auth()->user();
        $email = $request->get('email');
        if (!$email && $user) {
            $email = $user->email;
        }
        // If no email available at all, return a clear 400 error instead of 500
        if (!$email) {
            return response()->json([
                'message' => 'Parameter email is required',
                'data' => [],
                'current_page' => (int) $request->get('page', 1),
                'per_page' => (int) $request->get('per_page', 10),
                'total' => 0,
                'last_page' => 1
            ], 400);
        }

        $query = Nasabah::join('user_nasabahs', 'nasabah.id', '=', 'user_nasabahs.nasabah_id')
            ->join('cabang_users', 'user_nasabahs.id', '=', 'cabang_users.user_nasabah_id')
            ->join('cabangs', 'cabang_users.cabang_id', '=', 'cabangs.id')
            ->join('petugas_cabangs', 'cabangs.id', '=', 'petugas_cabangs.cabang_id')
            ->join('petugas', 'petugas_cabangs.petugas_id', '=', 'petugas.id')
            ->select(
                'nasabah.id',
                DB::raw('MAX(nasabah.status) as status'),
                DB::raw('MAX(nasabah.nama_lengkap) as nama_lengkap'),
                DB::raw('MAX(nasabah.no_registrasi) as no_registrasi'),
                DB::raw('MAX(nasabah.no_hp) as no_hp'),
                DB::raw('MAX(nasabah.cabang_id) as cabang_id'),
                DB::raw('GROUP_CONCAT(DISTINCT cabangs.nama_cabang) as nama_cabang')
            )
            ->where('petugas.email', $email)
            ->groupBy('nasabah.id');

        // Unified search: accept either individual filters or a generic 'search'
        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('nasabah.nama_lengkap', 'like', "%{$term}%")
                    ->orWhere('nasabah.no_registrasi', 'like', "%{$term}%");
            });
        } else {
            // 🔍 Filter berdasarkan nama nasabah
            if ($request->filled('nama_nasabah')) {
                $query->where('nasabah.nama_lengkap', 'like', '%' . $request->input('nama_nasabah') . '%');
            }
            // 🔍 Filter berdasarkan no_registrasi
            if ($request->filled('no_registrasi')) {
                $query->where('nasabah.no_registrasi', 'like', '%' . $request->input('no_registrasi') . '%');
            }
        }

        $perPage = (int) $request->get('per_page', 10);
        if ($perPage <= 0 || $perPage > 100) {
            $perPage = 10;
        }

        $nasabahs = $query->paginate($perPage);

        return response()->json($nasabahs);
    }

    /**
     * Daftar nasabah berdasarkan cabang (perorangan) dengan pagination & optional search.
     * Route: GET /api/cabangs/{cabang}/nasabah
     * Query params: search, page, per_page
     */
    public function byCabang(Request $request, $cabangId)
    {
        // Validasi per_page agar tidak berlebihan
        $perPage = (int) $request->get('per_page', 10);
        if ($perPage <= 0 || $perPage > 100) {
            $perPage = 10;
        }

        $query = Nasabah::join('user_nasabahs', 'nasabah.id', '=', 'user_nasabahs.nasabah_id')
            ->join('cabang_users', 'user_nasabahs.id', '=', 'cabang_users.user_nasabah_id')
            ->join('cabangs', 'cabang_users.cabang_id', '=', 'cabangs.id')
            ->select(
                'nasabah.id',
                'nasabah.no_registrasi',
                'nasabah.nama_lengkap',
                'nasabah.no_hp',
                'nasabah.status',
                'cabangs.nama_cabang',
                'cabangs.kode_cabang'
            )
            ->where('cabangs.id', $cabangId);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nasabah.nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nasabah.no_registrasi', 'like', "%{$search}%")
                    ->orWhere('nasabah.no_hp', 'like', "%{$search}%");
            });
        }

        $nasabahs = $query->orderBy('nasabah.nama_lengkap')->paginate($perPage);

        return response()->json($nasabahs);
    }


    public function requestWithdrawal(Request $request)
    {
        // 1. Validasi
        $validator = Validator::make($request->all(), [
            'jumlah_pencairan' => 'required|numeric|min:10000',
            'metode_pencairan_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $userNasabah = UserNasabah::with('nasabah')
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $nasabahId = $userNasabah->nasabah_id;
            $saldo = Saldo::where('nasabah_id', $nasabahId)->first();
            $adminPey = env('ADMIN_PEY', 0);

            // 2. Cek Saldo
            if (($saldo->saldo - $adminPey) < $request->jumlah_pencairan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Saldo tidak mencukupi untuk melakukan penarikan.'
                ], 400); // 400 Bad Request
            }

            // 3. Simpan pencairan
            $pencairan = PencairanSaldo::create([
                'nasabah_id' => $nasabahId,
                'jumlah_pencairan' => $request->jumlah_pencairan,
                'metode_id' => $request->metode_pencairan_id,
                'status' => 'pending',
            ]);

            $nasabah = $userNasabah->nasabah;

            // 4. Notifikasi WhatsApp
            if ($nasabah) {
                $setting = Setting::first();
                $pesan = "*Pemberitahuan Penarikan Saldo*\n\n"
                    . "Halo *{$nasabah->nama_lengkap}*,\n"
                    . "Permintaan pencairan saldo sebesar *Rp " . number_format($request->jumlah_pencairan, 0, ',', '.') . "* telah diterima.\n\n"
                    . "_Status: Pending_";

                $pesanAdmin = "*Pemberitahuan Admin*\n\n"
                    . "Nasabah *{$nasabah->nama_lengkap}* mengajukan pencairan sebesar *Rp " . number_format($request->jumlah_pencairan, 0, ',', '.') . "*";

                $this->whatsappService->sendMessage($setting->no_notifikasi, $pesanAdmin);
                $this->whatsappService->sendMessage($nasabah->no_hp, $pesan);
            }

            // 5. Response Sukses
            return response()->json([
                'success' => true,
                'message' => 'Pengajuan penarikan berhasil dikirim dan notifikasi telah dikirim.',
                'data'    => $pencairan
            ], 201); // 201 Created

        } catch (\Exception $e) {
            // Tangani jika terjadi error sistem
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}

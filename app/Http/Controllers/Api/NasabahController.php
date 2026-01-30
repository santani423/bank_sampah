<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MetodePencairan;
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
                        ->orWhere('nasabah.no_registrasi', 'like', "%{$search}%");
                });
            });

           
            $query->when($request->filled('cabang'), function ($q) use ($request) {
                $q->where('cabangs.kode_cabang', $request->cabang);
            });


            $query->when($request->filled('type'), function ($q) use ($request) {
                $q->where('nasabah.type', $request->type);
            });

       
            $perPage = (int) $request->get('per_page', 10);
            $perPage = ($perPage > 0 && $perPage <= 100) ? $perPage : 10;

          
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
        // 1. Validasi Request
        $validator = Validator::make($request->all(), [
            'jumlah_pencairan'    => 'required|numeric|min:10000',
            'metode_pencairan_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data yang dikirim tidak valid.',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            // 2. Ambil Data Nasabah
            $userNasabah = UserNasabah::with('nasabah')
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $nasabah   = $userNasabah->nasabah;
            $nasabahId = $userNasabah->nasabah_id;
            $saldo     = Saldo::where('nasabah_id', $nasabahId)->firstOrFail();
            $adminPey  = (float) env('ADMIN_PEY', 0);

            // 3. Cek Pencairan Pending (RULE BISNIS)
            $pendingExist = PencairanSaldo::where('nasabah_id', $nasabahId)
                ->where('status', 'pending')
                ->exists();

            if ($pendingExist) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda masih memiliki pengajuan pencairan yang sedang diproses. Mohon tunggu hingga selesai.'
                ], 400);
            }

            // 4. Cek Saldo
            if (($saldo->saldo - $adminPey) < $request->jumlah_pencairan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Saldo Anda tidak mencukupi untuk melakukan penarikan.'
                ], 400);
            }

            // 5. Ambil Metode Pencairan
            $metodePencairan = MetodePencairan::with('jenisMetodePenarikan')
                ->find($request->metode_pencairan_id);

            if (!$metodePencairan || !$metodePencairan->jenisMetodePenarikan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Metode pencairan tidak valid.'
                ], 400);
            }

            $jenisMetode = $metodePencairan->jenisMetodePenarikan;

            // 6. Hitung Fee
            $baseFee    = (float) ($jenisMetode->base_fee ?? 0);
            $ppnPercent = (float) ($jenisMetode->ppn_percent ?? 0);
            $ppnValue   = $baseFee * $ppnPercent / 100;
            $feeNet     = $baseFee + $ppnValue;

            $totalPencairan = $request->jumlah_pencairan;

            // Jika fee ditanggung nasabah
            if ($jenisMetode->fee_bearer !== 'COMPANY') {
                $totalPencairan -= $feeNet;
            }

            if ($totalPencairan <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah pencairan tidak valid setelah dikurangi biaya.'
                ], 400);
            }

            // 7. Simpan Pencairan (SESUAI STRUKTUR TABEL)
            $pencairan = PencairanSaldo::create([
                'nasabah_id'        => $nasabahId,
                'metode_id'         => $request->metode_pencairan_id,
                'jumlah_pencairan'  => $request->jumlah_pencairan,
                'total_pencairan'   => $totalPencairan,
                'ppn_percent'       => $ppnPercent,
                'fee_gross'         => $baseFee,
                'fee_net'           => $feeNet,
                'fee_bearer'        => $jenisMetode->fee_bearer === 'COMPANY'
                    ? 'COMPANY'
                    : 'NASABAH',
                'status'            => 'pending',
                'tanggal_pengajuan' => now(),
            ]);

            // 8. Notifikasi WhatsApp
            $setting = Setting::first();

            if ($nasabah && $setting) {

                $pesanNasabah =
                    "🔔 PEMBERITAHUAN PENARIKAN SALDO\n\n" .
                    "Yth. Bapak/Ibu *{$nasabah->nama_lengkap}*,\n\n" .
                    "Kami telah menerima pengajuan penarikan saldo dengan rincian berikut:\n\n" .
                    "────────────────────────\n" .
                    "📌 Detail Transaksi\n" .
                    "────────────────────────\n" .
                    "• Jumlah Pengajuan : Rp " . number_format($request->jumlah_pencairan, 0, ',', '.') . "\n" .
                    "• Biaya Administrasi : Rp " . number_format($feeNet, 0, ',', '.') . "\n" .
                    "• Jumlah Diterima : Rp " . number_format($totalPencairan, 0, ',', '.') . "\n" .
                    "• Metode Pencairan : {$metodePencairan->nama}\n" .
                    "• Tanggal Pengajuan : " . now()->format('d M Y H:i') . "\n" .
                    "• Status : *Menunggu Persetujuan Admin*\n\n" .
                    "────────────────────────\n" .
                    "Terima kasih atas kepercayaan Anda.\n\n" .
                    "Hormat kami,\n" .
                    "*BANK SAMPAH DIGITAL*";

                $pesanAdmin =
                    "🚨 NOTIFIKASI PENCAIRAN SALDO\n\n" .
                    "Pengajuan pencairan saldo baru telah masuk.\n\n" .
                    "────────────────────────\n" .
                    "👤 Data Nasabah\n" .
                    "────────────────────────\n" .
                    "• Nama : {$nasabah->nama_lengkap}\n" .
                    "• ID Nasabah : {$nasabahId}\n\n" .
                    "────────────────────────\n" .
                    "💰 Detail Pencairan\n" .
                    "────────────────────────\n" .
                    "• Jumlah Pengajuan : Rp " . number_format($request->jumlah_pencairan, 0, ',', '.') . "\n" .
                    "• Biaya Administrasi : Rp " . number_format($feeNet, 0, ',', '.') . "\n" .
                    "• Jumlah Diterima : Rp " . number_format($totalPencairan, 0, ',', '.') . "\n" .
                    "• Metode Pencairan : {$metodePencairan->nama}\n" .
                    "• Status : *Pending*";

                $this->whatsappService->sendMessage($setting->no_notifikasi, $pesanAdmin);
                $this->whatsappService->sendMessage($nasabah->no_hp, $pesanNasabah);
            }

            // 9. Response Sukses
            return response()->json([
                'success' => true,
                'message' => 'Pengajuan penarikan saldo berhasil diajukan.',
                'data'    => $pencairan
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada sistem.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    public function withdrawalList(Request $request)
    {
        try {
            $userNasabah = UserNasabah::where('user_id', auth()->id())->firstOrFail();
            $nasabahId = $userNasabah->nasabah_id;

            $query = PencairanSaldo::with(['metodePencairan.jenisMetodePenarikan'])
                ->where('nasabah_id', $nasabahId)
                ->orderByDesc('tanggal_pengajuan');

            // Pagination
            $perPage = (int) $request->get('per_page', 10);
            if ($perPage <= 0 || $perPage > 100) {
                $perPage = 10;
            }

            $pencairans = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Data pencairan berhasil diambil.',
                'data'    => $pencairans->items(),
                'pagination' => [
                    'current_page' => $pencairans->currentPage(),
                    'last_page'    => $pencairans->lastPage(),
                    'per_page'     => $pencairans->perPage(),
                    'total'        => $pencairans->total(),
                    'from'         => $pencairans->firstItem(),
                    'to'           => $pencairans->lastItem(),
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data pencairan.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}

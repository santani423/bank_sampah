<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nasabah;
use Illuminate\Support\Facades\DB;

class NasabahController extends Controller
{
    /**
     * Handle listing nasabah (perorangan) with pagination & search.
     */
    public function index(Request $request)
    {
        try {
            // =========================
            // 1. QUERY DASAR
            // =========================
            $query = Nasabah::query()->with(['saldo']);

            // =========================
            // 2. FILTER SEARCH
            // =========================
            $query->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($subQuery) use ($search) {
                    $subQuery->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('no_registrasi', 'like', "%{$search}%");
                });
            });

            // =========================
            // 3. VALIDASI PER PAGE
            // =========================
            $perPage = (int) $request->get('per_page', 10);
            $perPage = ($perPage > 0 && $perPage <= 100) ? $perPage : 10;

            // =========================
            // 4. PAGINATION
            // =========================
            $nasabahs = $query
                ->orderByDesc('id')
                ->paginate($perPage);

            // =========================
            // 5. RESPONSE SUKSES
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
            // =========================
            // 6. RESPONSE ERROR
            // =========================
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
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lapak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LapakController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Lapak::with('cabang');

            // Filter berdasarkan nama lapak
            if ($request->filled('nama_lapak')) {
                $query->where('nama_lapak', 'like', '%' . $request->nama_lapak . '%');
            }

            // Filter berdasarkan status approval
            if ($request->filled('approval_status')) {
                $query->where('approval_status', $request->approval_status);
            }

            // Filter berdasarkan cabang
            if ($request->filled('cabang_id')) {
                $query->where('cabang_id', $request->cabang_id);
            }

            $query->orderByDesc('created_at');

            // Pagination Laravel
            $perPage = $request->input('per_page', 10);
            $lapaks = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Data nasabah berhasil diambil.',
                'data' => $lapaks->items(),
                'pagination' => [
                    'current_page' => $lapaks->currentPage(),
                    'last_page'    => $lapaks->lastPage(),
                    'per_page'     => $lapaks->perPage(),
                    'total'        => $lapaks->total(),
                    'from'         => $lapaks->firstItem(),
                    'to'           => $lapaks->lastItem(),
                ],
            ], 200);
        } catch (\Throwable $e) {

            // Logging error
            Log::error('Error get lapak list', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data.',
            ], 500);
        }
    }
}

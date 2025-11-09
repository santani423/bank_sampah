<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nasabah;

class NasabahController extends Controller
{
    /**
     * Handle listing nasabah (perorangan) with pagination & search.
     */
    public function index(Request $request)
    {
        $query = Nasabah::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('no_registrasi', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->get('per_page', 10);
        if ($perPage <= 0 || $perPage > 100) { $perPage = 10; }

        $nasabahs = $query->orderByDesc('id')->paginate($perPage);
        return response()->json($nasabahs);
    }
}

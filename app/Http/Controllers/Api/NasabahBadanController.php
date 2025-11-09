<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NasabahBadan;

class NasabahBadanController extends Controller
{
    /**
     * List nasabah badan with search & pagination.
     */
    public function index(Request $request)
    {
        $query = NasabahBadan::with('jenisBadan');

        if ($search = $request->get('search')) {
            $query->where('nama_badan', 'like', "%{$search}%");
        }

        $perPage = (int) $request->get('per_page', 10);
        if ($perPage <= 0 || $perPage > 100) { $perPage = 10; }

        $nasabahs = $query->orderByDesc('id')->paginate($perPage);
        return response()->json($nasabahs);
    }
}

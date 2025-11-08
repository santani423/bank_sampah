<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\NasabahBadan;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/api/nasabah-badan', function (Request $request) {
        $query = NasabahBadan::with('jenisBadan');
        if ($request->has('search') && $request->search) {
            $query->where('nama_badan', 'like', '%' . $request->search . '%');
        }
        $nasabahs = $query->orderByDesc('id')->paginate(10);
        return response()->json($nasabahs);
    });
});

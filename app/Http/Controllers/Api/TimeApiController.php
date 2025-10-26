<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Time;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TimeApiController extends Controller
{
    // GET /api/times
    public function index()
    {
        $times = Time::latest()->get();
        return response()->json([
            'status' => 'success',
            'data' => $times
        ]);
    }

    // GET /api/times/{id}
    public function show($id)
    {
        $time = Time::find($id);

        if (!$time) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $time]);
    }

    // POST /api/times
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'jabatan' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $time = Time::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data tim berhasil ditambahkan',
            'data' => $time
        ], 201);
    }

    // POST /api/times/{id} — update
    public function update(Request $request, $id)
    {
        $time = Time::find($id);

        if (!$time) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'jabatan' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
        ]);

        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($time->avatar && Storage::disk('public')->exists($time->avatar)) {
                Storage::disk('public')->delete($time->avatar);
            }

            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $time->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data tim berhasil diperbarui',
            'data' => $time
        ]);
    }

    // DELETE /api/times/{id}
    public function destroy($id)
    {
        $time = Time::find($id);

        if (!$time) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);
        }

        // Hapus avatar dari storage jika ada
        if ($time->avatar && Storage::disk('public')->exists($time->avatar)) {
            Storage::disk('public')->delete($time->avatar);
        }

        $time->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data tim berhasil dihapus'
        ]);
    }
}

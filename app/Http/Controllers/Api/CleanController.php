<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CleanResource;
use App\Models\Clean;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CleanController extends Controller
{
    // GET /api/cleans
    public function index(Request $request)
    {
        $perPage = $request->get('per_page'); // ambil parameter jika ada

        $query = Clean::orderBy('created_at', 'desc');

        // Jika tidak ada parameter per_page → tampilkan semua data
        if (!$perPage) {
            $cleans = $query->get();
            return CleanResource::collection($cleans);
        }

        // Jika ada per_page → gunakan pagination
        $cleans = $query->paginate($perPage);
        return CleanResource::collection($cleans);
    }


    // POST /api/cleans
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('uploads/cleans', 'public');
        }

        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(5);

        $clean = Clean::create($validated);

        return new CleanResource($clean);
    }

    // GET /api/cleans/{id}
    public function show($id)
    {
        $clean = Clean::findOrFail($id);
        return new CleanResource($clean);
    }

    // PUT /api/cleans/{id}
    public function update(Request $request, $id)
    {
        $clean = Clean::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'status' => 'sometimes|required|in:active,inactive',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('uploads/cleans', 'public');
        }

        if (isset($validated['title'])) {
            $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(5);
        }

        $clean->update($validated);

        return new CleanResource($clean);
    }

    // DELETE /api/cleans/{id}
    public function destroy($id)
    {
        $clean = Clean::findOrFail($id);
        $clean->delete();

        return response()->json(['message' => 'Clean deleted successfully.']);
    }
}

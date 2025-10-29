<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ActivityController extends Controller
{
    // List semua activity
    public function index(Request $request)
    {
        // Ambil parameter 'size' dan 'page' dari request, dengan default value
        $size = $request->input('size', 10); // default 10 item per halaman
        $page = $request->input('page', 1);  // default halaman pertama

        // Ambil data dengan pagination
        $activities = Activity::with('label')
            ->latest()
            ->paginate($size, ['*'], 'page', $page);

        // Return hasil sebagai resource collection
        return ActivityResource::collection($activities);
    }


    // Menyimpan activity baru
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'label_id' => 'required|exists:labels,id',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'location' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'status' => 'nullable|in:active,inactive',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);

        // Upload image jika ada
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images/activities', 'public');
        }

        $activity = Activity::create($data);

        return new ActivityResource($activity);
    }

    // Menampilkan satu activity
    public function show($slug)
    {
        // Ambil activity beserta label berdasarkan slug
        $activity = Activity::with('label')->where('slug', $slug)->firstOrFail();

        // Decode HTML entities untuk content dan description agar iframe/HTML tampil di frontend
        $fieldsToDecode = ['content', 'description'];
        foreach ($fieldsToDecode as $field) {
            if (!empty($activity->$field)) {
                $activity->$field = html_entity_decode($activity->$field);
            }
        }

        return new ActivityResource($activity);
    }



    // Update activity
    public function update(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'label_id' => 'required|exists:labels,id',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'location' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'status' => 'nullable|in:active,inactive',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);

        // Upload image jika ada
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images/activities', 'public');
        }

        $activity->update($data);

        return new ActivityResource($activity);
    }

    // Hapus activity
    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);
        $activity->delete();

        return response()->json(['message' => 'Activity berhasil dihapus.']);
    }
}

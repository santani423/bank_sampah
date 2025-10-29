<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ActivityController extends Controller
{
    // Menampilkan daftar activity
    public function index(Request $request)
    {
        $query = Activity::with('label');
        $perPage = (int) $request->get('per_page', 10);
        $page = max(1, (int) $request->get('page', 1));
        // Filter pencarian berdasarkan title
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        // Urutkan berdasarkan created_at terbaru (optional)
        $query->orderByDesc('created_at');

        // Pagination
        $activities = $query->paginate($perPage, ['*'], 'page', $page);

        return view('pages.admin.activities.index', compact('activities'));
    }


    // Menampilkan form create
    public function create()
    {
        $labels = Label::all();
        return view('pages.admin.activities.create', compact('labels'));
    }

    // Simpan activity baru
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'label_id' => 'nullable|exists:labels,id',
            'status' => 'required|in:active,inactive',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/activities'), $imageName);
            $imagePath = 'uploads/activities/' . $imageName;
        }

        Activity::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'content' => $request->content,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'location' => $request->location,
            'image' => $imagePath,
            'label_id' => $request->label_id,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.activities.index')->with('success', 'Activity created successfully');
    }


    // Tampilkan detail activity
    public function show($id)
    {
        $activity = Activity::with('label')->findOrFail($id);
        return view('pages.admin.activities.show', compact('activity'));
    }

    // Menampilkan form edit
    public function edit($id)
    {
        $activity = Activity::findOrFail($id);
        $labels = Label::all();
        return view('pages.admin.activities.edit', compact('activity', 'labels'));
    }

    // Update activity
    public function update(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'label_id' => 'nullable|exists:labels,id',
            'status' => 'required|in:active,inactive',
        ]);

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'content' => $request->content,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'location' => $request->location,
            'label_id' => $request->label_id,
            'status' => $request->status,
        ];

        // Cek jika ada file gambar baru
        if ($request->hasFile('image')) {
            // Hapus file lama jika ada (opsional)
            if ($activity->image && file_exists(public_path($activity->image))) {
                unlink(public_path($activity->image));
            }

            // Simpan file baru
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('uploads/activities'), $imageName);
            $data['image'] = 'uploads/activities/' . $imageName;
        }

        $activity->update($data);

        return redirect()->route('admin.activities.index')->with('success', 'Activity updated successfully');
    }


    // Hapus activity
    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);
        $activity->delete();

        return redirect()->route('admin.activities.index')->with('success', 'Activity deleted successfully');
    }
}

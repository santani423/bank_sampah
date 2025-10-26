<?php

namespace App\Http\Controllers;

use App\Models\Clean;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CleanController extends Controller
{
    /**
     * Tampilkan semua data Clean.
     */
    public function index()
    {
        $cleans = Clean::orderBy('created_at', 'desc')->get();
        return view('pages.admin.cleans.index', compact('cleans'));
    }

    /**
     * Form tambah data.
     */
    public function create()
    {
        return view('pages.admin.cleans.create');
    }

    /**
     * Simpan data baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'      => 'required|in:active,inactive',
        ]);

        $clean = new Clean();
        $clean->title = $validated['title'];
        $clean->slug = Str::slug($validated['title']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('cleans', 'public');
            $clean->image = $path;
        }

        $clean->description = $validated['description'] ?? null;
        $clean->status = $validated['status'];
        $clean->save();

        return redirect()->route('admin.cleans.index')->with('success', 'Data berhasil disimpan!');
    }

    /**
     * Tampilkan detail data.
     */
    public function show($id)
    {
        $clean = Clean::findOrFail($id);
        return view('pages.admin.cleans.show', compact('clean'));
    }

    /**
     * Form edit data.
     */
    public function edit($id)
    {
        $clean = Clean::findOrFail($id);
        return view('pages.admin.cleans.edit', compact('clean'));
    }

    /**
     * Update data.
     */
    public function update(Request $request, $id)
    {
        $clean = Clean::findOrFail($id);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'      => 'required|in:active,inactive',
        ]);

        $clean->title = $validated['title'];
        $clean->slug = Str::slug($validated['title']);
        $clean->description = $validated['description'] ?? null;
        $clean->status = $validated['status'];

        if ($request->hasFile('image')) {
            if ($clean->image && Storage::disk('public')->exists($clean->image)) {
                Storage::disk('public')->delete($clean->image);
            }
            $path = $request->file('image')->store('cleans', 'public');
            $clean->image = $path;
        }

        $clean->save();

        return redirect()->route('admin.cleans.index')->with('success', 'Data berhasil diperbarui!');
    }

    /**
     * Hapus data.
     */
    public function destroy($id)
    {
        $clean = Clean::findOrFail($id);

        if ($clean->image && Storage::disk('public')->exists($clean->image)) {
            Storage::disk('public')->delete($clean->image);
        }

        $clean->delete();

        return redirect()->route('admin.cleans.index')->with('success', 'Data berhasil dihapus!');
    }
}

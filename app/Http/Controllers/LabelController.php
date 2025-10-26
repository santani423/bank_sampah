<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LabelController extends Controller
{
    public function index()
    {
        $labels = Label::latest()->paginate(10);
        return view('pages.admin.labels.index', compact('labels'));
    }

    public function create()
    {
        return view('pages.admin.labels.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:labels,name',
            'color' => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ]);

        Label::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'color' => $request->color,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.labels.index')->with('success', 'Label berhasil ditambahkan!');
    }

    public function edit($id)
    {
        // Ambil label dari database
        $label = Label::findOrFail($id);

        return view('pages.admin.labels.edit', compact('label'));
    }


    public function update(Request $request, $id)
    {
        $label = Label::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255|unique:labels,name,' . $label->id,
            'color' => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ]);

        $label->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'color' => $request->color,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.labels.index')->with('success', 'Label berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $label = Label::findOrFail($id);
        $label->delete();

        return redirect()->route('admin.labels.index')->with('success', 'Label berhasil dihapus!');
    }
}

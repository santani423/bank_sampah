<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Menampilkan halaman pengaturan.
     */
    public function index()
    {
        $setting = Setting::first();
        return view('pages.admin.settings.index', compact('setting'));
    }

    /**
     * Update data setting.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'keywords' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'google_map' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'youtube' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
            'no_notifikasi' => 'nullable|string|max:255',
            'min_penarikan' => 'nullable|numeric|min:0',
            'max_penarikan' => 'nullable|numeric|min:0',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,ico,webp|max:1024',
        ]);

        $setting = Setting::findOrFail($id);

        // Update kolom teks langsung
        $setting->fill([
            'nama' => $request->nama,
            'title' => $request->title,
            'description' => $request->description,
            'keywords' => $request->keywords,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'google_map' => $request->google_map,
            'whatsapp' => $request->whatsapp,
            'instagram' => $request->instagram,
            'twitter' => $request->twitter,
            'youtube' => $request->youtube,
            'tiktok' => $request->tiktok,
            'no_notifikasi' => $request->no_notifikasi,
            'min_penarikan' => $request->min_penarikan,
            'max_penarikan' => $request->max_penarikan,
        ]);

        // === Upload Logo Baru (jika ada) ===
        if ($request->hasFile('logo')) {
            if ($setting->logo && Storage::disk('public')->exists($setting->logo)) {
                Storage::disk('public')->delete($setting->logo);
            }
            $path = $request->file('logo')->store('logos', 'public');
            $setting->logo = 'storage/' . $path;
        }

        // === Upload Favicon Baru (jika ada) ===
        if ($request->hasFile('favicon')) {
            if ($setting->favicon && Storage::disk('public')->exists($setting->favicon)) {
                Storage::disk('public')->delete($setting->favicon);
            }
            $path = $request->file('favicon')->store('favicons', 'public');
            $setting->favicon =  'storage/' . $path;
        }

        $setting->save();

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Pengaturan berhasil diperbarui.');
    }
}

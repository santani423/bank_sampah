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
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'min_penarikan' => 'nullable|numeric|min:0',
            'max_penarikan' => 'nullable|numeric|min:0',
            'no_notifikasi' => 'nullable|numeric|min:0',
        ]);

        $setting = Setting::findOrFail($id);
        $setting->nama = $request->nama;
        $setting->min_penarikan = $request->min_penarikan;
        $setting->max_penarikan = $request->max_penarikan;
        $setting->no_notifikasi = $request->no_notifikasi;

        // Jika user upload logo baru
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($setting->logo && Storage::disk('public')->exists($setting->logo)) {
                Storage::disk('public')->delete($setting->logo);
            }

            // Simpan logo baru
            $path = $request->file('logo')->store('logos', 'public');
            $setting->logo = $path;
        }

        $setting->save();

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Pengaturan berhasil diperbarui.');
    }
}

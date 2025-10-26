<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\cabang;
use App\Models\CabangUser;
use App\Models\UserNasabah;
use Illuminate\Http\Request;

class CabangController extends Controller
{
    public function index()
    {
        // Logic for displaying the cabang
        $cabangList = cabang::get();
        // dd($cabangList[0]);
        $userCabang = CabangUser::join('user_nasabahs', 'user_nasabahs.id', '=', 'cabang_users.user_nasabah_id')
            ->join('cabangs', 'cabangs.id', '=', 'cabang_users.cabang_id')
            ->select('cabangs.*')
            ->where('user_nasabahs.user_id', auth()->id())
            ->get();
        return view('pages.nasabah.cabang', compact('cabangList', 'userCabang'));
    }

    public function store(Request $request)
    {
        // Ambil data nasabah yang login
        $nasabah = UserNasabah::where('user_id', auth()->id())->first();

        // Ambil cabang yang ingin digabung
        $cbg = Cabang::findOrFail($request->id);

        // Cek apakah user sudah tergabung dengan cabang ini
        $cabangNasabah = CabangUser::where('user_nasabah_id', $nasabah->id)->first();

        if ($cabangNasabah) {
            // Jika sudah ada, update cabang_id
            $cabangNasabah->cabang_id = $request->id;
            $cabangNasabah->save();

            return redirect()->route('nasabah.cabang.index')
                ->with('success', 'Cabang Anda telah diperbarui menjadi ' . $cbg->nama_cabang);
        } else {
            // Jika belum ada, buat record baru
            $cabang = new CabangUser;
            $cabang->cabang_id = $request->id;
            $cabang->user_nasabah_id = $nasabah->id;
            $cabang->save();

            return redirect()->route('nasabah.cabang.index')
                ->with('success', 'Anda telah bergabung dengan cabang ' . $cbg->nama_cabang);
        }
    }
}

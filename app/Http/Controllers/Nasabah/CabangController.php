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
        // Logic for joining a cabang
        $nasabah =  UserNasabah::where('user_id', auth()->id())->first();

        $cbg = cabang::findOrFail($request->id);
        $cabangNasabah = CabangUser::where('user_nasabah_id', $nasabah->id)->where('cabang_id', $request->id)->first();

        if ($cabangNasabah) {
            return redirect()->route('nasabah.cabang.index')->with('error', 'Anda sudah bergabung dengan cabang ' . $cbg->nama_cabang);
        }
 
        $cabang = new CabangUser;

        $cabang->cabang_id = $request->id;
        $cabang->user_nasabah_id = $nasabah->id; 
        $cabang->save(); 
        return redirect()->route('nasabah.cabang.index')->with('success', 'Anda telah bergabung dengan cabang ' . $cbg->nama_cabang);
    }
}

<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\JenisMetodePenarikan;
use App\Models\MetodePencairan;
use App\Models\UserNasabah;
use Illuminate\Http\Request;

class MetodePenarikanController extends Controller
{
    public function index()
    {
        $jenisMetodePenarikan = JenisMetodePenarikan::all();
        // Logic for displaying the list of withdrawal methods
        $userNasabah = UserNasabah::where('user_id', auth()->id())->first();
        $metodePenarikan = MetodePencairan::where('nasabah_id', $userNasabah->nasabah_id)
            ->with('jenisMetodePenarikan')
            ->get();
        return view('pages.nasabah.metode-penarikan.index', compact('jenisMetodePenarikan','metodePenarikan'));
    }

    public function create()
    {
        // Logic for showing the form to create a new withdrawal method
        return view('pages.nasabah.metode-penarikan.create');
    }

    public function store(Request $request)
    {
        // Logic for storing a new withdrawal method
        // Validate and save the method
        $request->validate([
            'nama_metode_pencairan' => 'required|string|max:255',
            'no_rek' => 'required|string|max:20',
            'jenis_metode_penarikan_id' => 'required',
        ]);
        // Assuming you have a model for the withdrawal method
        $userNasabah = UserNasabah::where('user_id', auth()->id())->first();

        $metodePenarikan = new MetodePencairan();
        $metodePenarikan->nasabah_id = $userNasabah->nasabah_id;
        $metodePenarikan->jenis_metode_penarikan_id = $request->jenis_metode_penarikan_id;
        $metodePenarikan->nama_metode_pencairan = $request->nama_metode_pencairan;
        $metodePenarikan->no_rek = $request->no_rek;
        $metodePenarikan->save();
        return redirect()->route('nasabah.metode-penarikan.index')->with('success', 'Metode penarikan berhasil ditambahkan.');
    }
}

<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Lapak;
use App\Models\Cabang;
use App\Models\JenisMetodePenarikan;
use App\Models\Petugas;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class LapakController extends Controller
{
    /**
     * Proses kirim sampah dari lapak
     */
    public function prosesKirimSampah(Request $request, $lapakId)
    {
        // Validasi data
        $request->validate([
            'tanggal_pengiriman' => 'required|date',
            'jenis_sampah' => 'required|string',
            'berat' => 'required|numeric|min:0.01',
        ]);

        // Simpan data pengiriman ke database (contoh, sesuaikan dengan struktur tabel Anda)
        DB::table('pengiriman_sampah')->insert([
            'lapak_id' => $lapakId,
            'tanggal_pengiriman' => $request->tanggal_pengiriman,
            'jenis_sampah' => $request->jenis_sampah,
            'berat' => $request->berat,
            'catatan' => $request->catatan,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Alert::success('Berhasil', 'Pengiriman sampah berhasil disimpan');
        return redirect()->route('petugas.lapak.index');
    }
    /**
     * Tampilkan halaman pengiriman sampah dari lapak
     */
    public function kirimSampah($lapakId)
    {
        $lapak = Lapak::findOrFail($lapakId);
        return view('pages.petugas.lapak.kirim-sampah', compact('lapak'));
    }
    /**
     * Tampilkan detail transaksi lapak
     */
    public function showTransaksi($id)
    {
        $transaksi = DB::table('transaksi_lapak')->where('id', $id)->first();
        if (!$transaksi) abort(404);
        $transaksi->detail_transaksi = DB::table('detail_transaksi_lapak')
            ->where('transaksi_lapak_id', $id)
            ->get()
            ->map(function ($detail) {
                $detail->sampah = DB::table('sampah')->where('id', $detail->sampah_id)->first();
                return $detail;
            });
        $transaksi->petugas = DB::table('petugas')->where('id', $transaksi->petugas_id)->first();
        // Pastikan properti status selalu ada
        if (!property_exists($transaksi, 'status')) {
            $transaksi->status = $transaksi->approval ?? 'pending';
        }
        return view('pages.petugas.lapak.transaksi.detail', compact('transaksi'));
    }

    /**
     * Download detail transaksi lapak dalam format khusus
     */
    public function downloadTransaksi($id)
    {
        $transaksi = DB::table('transaksi_lapak')->where('id', $id)->first();
        if (!$transaksi) abort(404);
        $details = DB::table('detail_transaksi_lapak')
            ->where('transaksi_lapak_id', $id)
            ->get()
            ->map(function ($detail) {
                $detail->sampah = DB::table('sampah')->where('id', $detail->sampah_id)->first();
                return $detail;
            });
        $transaksi->detail_transaksi = $details;
        $transaksi->petugas = DB::table('petugas')->where('id', $transaksi->petugas_id)->first();
        $transaksi->status = property_exists($transaksi, 'status') ? $transaksi->status : ($transaksi->approval ?? 'pending');
        $pdf = \PDF::loadView('pages.petugas.lapak.transaksi.pdf', compact('transaksi'));
        $filename = 'detail_transaksi_lapak_' . $transaksi->kode_transaksi . '.pdf';
        return $pdf->download($filename);
    }
    /**
     * Simpan transaksi setor sampah lapak
     */
    public function storeSetorSampah(Request $request, $lapakId)
    {
        $request->validate([
            'kode_transaksi' => 'required|string|unique:transaksi_lapak,kode_transaksi',
            'tanggal_transaksi' => 'required|date',
            'detail_transaksi' => 'required|array|min:1',
            'detail_transaksi.*.sampah_id' => 'required|exists:sampah,id',
            'detail_transaksi.*.berat_kg' => 'required|numeric|min:0',
            'detail_transaksi.*.harga_per_kg' => 'required|numeric|min:0',
        ]);

        $totalTransaksi = 0;
        foreach ($request->detail_transaksi as $detail) {
            $totalTransaksi += $detail['berat_kg'] * $detail['harga_per_kg'];
        }

        $petugas = \App\Models\Petugas::where('email', auth()->user()->email)->first();

        $transaksiLapak = \DB::transaction(function () use ($request, $lapakId, $totalTransaksi, $petugas) {
            $transaksi = \DB::table('transaksi_lapak')->insertGetId([
                'lapak_id' => $lapakId,
                'kode_transaksi' => $request->kode_transaksi,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'total_transaksi' => $totalTransaksi,
                'approval' => 'pending',
                'keterangan' => $request->keterangan ?? null,
                'petugas_id' => $petugas ? $petugas->id : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($request->detail_transaksi as $detail) {
                \DB::table('detail_transaksi_lapak')->insert([
                    'transaksi_lapak_id' => $transaksi,
                    'sampah_id' => $detail['sampah_id'],
                    'berat_kg' => $detail['berat_kg'],
                    'harga_per_kg' => $detail['harga_per_kg'],
                    'total_harga' => $detail['berat_kg'] * $detail['harga_per_kg'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            return $transaksi;
        });

        \RealRashid\SweetAlert\Facades\Alert::success('Berhasil', 'Transaksi setor sampah lapak berhasil disimpan dan menunggu approval admin');
        return redirect()->route('petugas.lapak.index');
    }
    /**
     * Tampilkan halaman setor sampah untuk lapak
     */
    public function setorSampah($lapakId)
    {
        $lapak = Lapak::findOrFail($lapakId);
        return view('pages.petugas.lapak.setor-sampah', compact('lapak'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil cabang petugas yang sedang login
        $petugasCabangIds = $this->getPetugasCabangIds();

        $query = Lapak::with('cabang')
            ->whereIn('cabang_id', $petugasCabangIds);

        // Filter berdasarkan nama lapak
        if ($request->filled('nama_lapak')) {
            $query->where('nama_lapak', 'like', '%' . $request->input('nama_lapak') . '%');
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $lapaks = $query->paginate(10);
        $jenisMetodePenarikan = JenisMetodePenarikan::all();

        return view('pages.petugas.lapak.index', compact('lapaks', 'jenisMetodePenarikan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil cabang yang terkait dengan petugas yang sedang login
        $petugasCabangIds = $this->getPetugasCabangIds();
        $cabangs = Cabang::whereIn('id', $petugasCabangIds)
            ->where('status', 'aktif')
            ->orderBy('nama_cabang')
            ->get();

        // Generate kode lapak otomatis
        $lastLapak = Lapak::latest('id')->first();
        $nextNumber = $lastLapak ? intval(substr($lastLapak->kode_lapak, 3)) + 1 : 1;
        $kodeLapak = 'LPK' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        $jenisMetodePenarikan = JenisMetodePenarikan::all();

        return view('pages.petugas.lapak.create', compact('cabangs', 'kodeLapak', 'jenisMetodePenarikan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cabang_id' => 'required|exists:cabangs,id',
            'kode_lapak' => 'required|unique:lapak,kode_lapak|max:50',
            'nama_lapak' => 'required|string|max:100',
            'alamat' => 'required|string',
            'kota' => 'nullable|string|max:50',
            'provinsi' => 'nullable|string|max:50',
            'kode_pos' => 'nullable|string|max:10',
            'no_telepon' => 'nullable|string|max:20',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:aktif,tidak_aktif',
            'jenis_metode_penarikan_id' => 'required|exists:jenis_metode_penarikans,id',
            'nama_rekening' => 'required|string|max:100',
            'nomor_rekening' => 'required|string|max:50',
        ]);

        $data = $request->all();

        // Upload foto jika ada
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/lapak'), $filename);
            $data['foto'] = $filename;
        }

        // Set default approval status ke pending dan status ke tidak_aktif
        $data['approval_status'] = 'pending';
        $data['status'] = 'tidak_aktif'; // Tidak aktif sampai di-approve admin 

        Lapak::create($data);
        Alert::success('Berhasil', 'Data lapak berhasil ditambahkan dan menunggu persetujuan admin');
        return redirect()->route('petugas.lapak.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lapak = Lapak::with('cabang', 'jenisMetodePenarikan')->findOrFail($id);
        // dd($lapak);
        return view('pages.petugas.lapak.show', compact('lapak'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $lapak = Lapak::findOrFail($id);

        // Ambil cabang yang terkait dengan petugas yang sedang login
        $petugasCabangIds = $this->getPetugasCabangIds();
        $cabangs = Cabang::whereIn('id', $petugasCabangIds)
            ->where('status', 'aktif')
            ->orderBy('nama_cabang')
            ->get();
        $jenisMetodePenarikan = JenisMetodePenarikan::all();

        return view('pages.petugas.lapak.edit', compact('lapak', 'cabangs', 'jenisMetodePenarikan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $lapak = Lapak::findOrFail($id);

        $request->validate([
            'cabang_id' => 'required|exists:cabangs,id',
            'kode_lapak' => 'required|max:50|unique:lapak,kode_lapak,' . $id,
            'nama_lapak' => 'required|string|max:100',
            'alamat' => 'required|string',
            'kota' => 'nullable|string|max:50',
            'provinsi' => 'nullable|string|max:50',
            'kode_pos' => 'nullable|string|max:10',
            'no_telepon' => 'nullable|string|max:20',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:aktif,tidak_aktif',
            'jenis_metode_penarikan_id' => 'required|exists:jenis_metode_penarikans,id',
            'nama_rekening' => 'required|string|max:100',
            'nomor_rekening' => 'required|string|max:50',
        ]);

        $data = $request->all();

        // Upload foto baru jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($lapak->foto && file_exists(public_path('uploads/lapak/' . $lapak->foto))) {
                unlink(public_path('uploads/lapak/' . $lapak->foto));
            }

            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/lapak'), $filename);
            $data['foto'] = $filename;
        } else {
            unset($data['foto']);
        }

        // Set approval_status ke pending dan status ke tidak_aktif setiap update
        $data['approval_status'] = 'pending';
        $data['status'] = 'tidak_aktif';

        $lapak->update($data);

        Alert::success('Berhasil', 'Data lapak berhasil diperbarui dan menunggu persetujuan admin');
        return redirect()->route('petugas.lapak.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lapak = Lapak::findOrFail($id);

        // Hapus foto jika ada
        if ($lapak->foto && file_exists(public_path('uploads/lapak/' . $lapak->foto))) {
            unlink(public_path('uploads/lapak/' . $lapak->foto));
        }

        $lapak->delete();

        Alert::success('Berhasil', 'Data lapak berhasil dihapus');
        return redirect()->route('petugas.lapak.index');
    }

    /**
     * Helper method untuk mendapatkan ID cabang yang terkait dengan petugas yang sedang login
     */
    private function getPetugasCabangIds()
    {
        $petugas = Petugas::where('email', auth()->user()->email)->first();

        if (!$petugas) {
            return [];
        }

        return DB::table('petugas_cabangs')
            ->where('petugas_id', $petugas->id)
            ->pluck('cabang_id')
            ->toArray();
    }
}

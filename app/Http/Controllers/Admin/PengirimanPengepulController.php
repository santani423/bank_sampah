<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\cabang;
use Illuminate\Http\Request;
use App\Models\Pengepul;
use App\Models\Sampah;
use App\Models\PengirimanPengepul;
use App\Models\DetailPengiriman;
use App\Models\FilePengirimanPetugas;
use App\Models\Gudang;
use App\Models\PengirimanPetugas;
use App\Models\Petugas;
use App\Models\RefFilePengirimanPetugas;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengirimanPengepulController extends Controller
{
    public function index()
    {
        // // Ambil data pengiriman sampah dengan relasi ke pengepul
        // $pengirimanSampah = PengirimanPengepul::with('pengepul')
        //     ->paginate(10);

        // // Transformasi data langsung pada koleksi paginator
        // $pengirimanSampah->getCollection()->transform(function ($pengiriman) {
        //     // Hitung total berat sampah dalam pengiriman ini
        //     $pengiriman->total_berat = DetailPengiriman::where('pengiriman_id', $pengiriman->id)
        //         ->sum('berat_kg');

        //     // Hitung jumlah jenis sampah yang berbeda dalam pengiriman ini
        //     $pengiriman->jumlah_jenis_sampah = DetailPengiriman::where('pengiriman_id', $pengiriman->id)
        //         ->distinct('sampah_id')
        //         ->count('sampah_id');

        //     return $pengiriman;
        // });

        // // Tampilkan ke view


        $pengirimanSampah = PengirimanPetugas::with('detailPengiriman', 'gudang', 'cabang', 'petugas')->get();
        // dd($pengirimanSampah);
        return view('pages.admin.pengiriman.index', compact('pengirimanSampah'));
    }

    public function generateUniqueShippingCode()
    {
        // Format: BSR-YYYYMMDD-PENG-001
        $today = now()->format('Ymd');
        $prefix = "BSR-{$today}-PENG-";

        // Cari kode pengiriman terakhir hari ini
        $lastShipping = PengirimanPetugas::where('kode_pengiriman', 'like', $prefix . '%')
            ->orderBy('kode_pengiriman', 'desc')
            ->first();

        if (!$lastShipping) {
            // Jika belum ada pengiriman hari ini, mulai dari 001
            return $prefix . '001';
        }

        // Ekstrak nomor urut terakhir
        $lastNumber = substr($lastShipping->kode_pengiriman, -3);
        $newNumber = str_pad((int)$lastNumber + 1, 3, '0', STR_PAD_LEFT);

        return $prefix . $newNumber;
    }


    public function create()
    {
        $pengepulList = Pengepul::all();
        $kodePengiriman = $this->generateUniqueShippingCode();

        $stokSampah = Sampah::with(['detailTransaksi', 'detailPengiriman'])
            ->get()
            ->map(function ($sampah) {
                $totalSetoran = $sampah->detailTransaksi->sum('berat_kg');
                $totalPengiriman = $sampah->detailPengiriman->sum('berat_kg');
                $sampah->stok = $totalSetoran - $totalPengiriman;
                return $sampah;
            });


        $cabang = cabang::join('petugas_cabangs as pc', 'pc.cabang_id', '=', 'cabangs.id')
            ->join('petugas as p', 'p.id', '=', 'pc.petugas_id')
            ->join('users as u', 'u.email', '=', 'p.email')
            ->where('u.id', Auth::id()) // filter berdasarkan user login
            ->select('cabangs.*')
            ->distinct() // hindari duplikat data cabang
            ->get();


        $gudang = Gudang::all();


        $refUpladPengiriman = RefFilePengirimanPetugas::orderBy('urutan', 'ASC')->get();


        return view('pages.admin.pengiriman.create', compact('pengepulList', 'stokSampah', 'kodePengiriman', 'cabang', 'gudang', 'refUpladPengiriman'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cabang_id'           => 'required|exists:cabangs,id',
            'gudang_id'           => 'required|exists:gudangs,id',
            'tanggal_pengiriman'  => 'required|date',
            'catatan'             => 'nullable|string|max:1000',
            'sampah_id'           => 'required|array|min:1',
            'sampah_id.*'         => 'exists:sampah,id',
            'berat_kg'            => 'required|array|min:1',
            'berat_kg.*'          => 'numeric|min:0.01',
            'file_upload'         => 'nullable|array',
            'file_upload.*'       => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'ref_file_id'         => 'nullable|array',
            'ref_file_id.*'       => 'nullable|integer|exists:ref_file_pengiriman_petugas,id',
        ]);

        try {
            // Ambil user dan petugas aktif
            $user = User::findOrFail(Auth::id());
            $petugas = Petugas::where('email', $user->email)->firstOrFail();

            // Buat data pengiriman utama
            $pengiriman = PengirimanPetugas::create([
                'kode_pengiriman'    => $request->kode_pengiriman,
                'cabang_id'          => $request->cabang_id,
                'gudang_id'          => $request->gudang_id,
                'petugas_id'         => $petugas->id,
                'tanggal_pengiriman' => $request->tanggal_pengiriman,
                'catatan'            => $request->catatan,
            ]);

            // === Simpan Detail Sampah ===
            foreach ($request->sampah_id as $index => $sampahId) {
                $beratPengiriman = $request->berat_kg[$index];

                // Ambil stok dinamis
                $stokSampah = Sampah::with(['detailTransaksi', 'detailPengiriman'])->findOrFail($sampahId);
                $totalSetoran = $stokSampah->detailTransaksi->sum('berat_kg');
                $totalPengiriman = $stokSampah->detailPengiriman->sum('berat_kg');
                $stokTersedia = $totalSetoran - $totalPengiriman;

                // Validasi stok
                if ($beratPengiriman > $stokTersedia) {
                    return back()
                        ->withErrors([
                            'error' => "Stok sampah '{$stokSampah->nama_sampah}' tidak mencukupi. Stok tersedia hanya {$stokTersedia} kg."
                        ])
                        ->withInput();
                }

                DetailPengiriman::create([
                    'pengiriman_id' => $pengiriman->id,
                    'sampah_id'     => $sampahId,
                    'berat_kg'      => $beratPengiriman,
                ]);
            }

            // === Upload File Pendukung ===
            if ($request->hasFile('file_upload')) {
                foreach ($request->file('file_upload') as $index => $file) {
                    if ($file && isset($request->ref_file_id[$index])) {
                        $fileName = time() . '_' . $file->getClientOriginalName();
                        $path = $file->storeAs('pengiriman', $fileName, 'public');

                        FilePengirimanPetugas::create([
                            'pengiriman_petugas_id' => $pengiriman->id,
                            'ref_file_id'           => $request->ref_file_id[$index],
                            'nama_file'             => $fileName,
                            'path_file'             => 'storage/' . $path,
                            'uploaded_by'           => Auth::id(),
                            'uploaded_at'           => now(),
                        ]);
                    }
                }
            }

            return redirect()
                ->route('petugas.pengiriman.index')
                ->with('success', 'Pengiriman dan file berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat menyimpan data pengiriman: ' . $e->getMessage()
            ])->withInput();
        }
    }



    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $pengiriman = PengirimanPengepul::findOrFail($id);
            $pengiriman->details()->delete();  // Menghapus semua detail pengiriman
            $pengiriman->delete();             // Menghapus pengiriman utama

            DB::commit();
            return redirect()->route('admin.pengiriman.index')->with('success', 'Pengiriman berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data pengiriman: ' . $e->getMessage());
        }
    }

    public function show($code)
    {
        $pengiriman = PengirimanPetugas::with('detailPengiriman', 'gudang', 'cabang', 'petugas')->where('kode_pengiriman', $code)->first();
        return view('pages.admin.pengiriman.show', compact('pengiriman'));
    }

    public function edit($code)
    {
        $pengiriman = PengirimanPetugas::with('detailPengiriman', 'gudang', 'cabang', 'petugas')->where('kode_pengiriman', $code)->first();
        $cabang = cabang::join('petugas_cabangs as pc', 'pc.cabang_id', '=', 'cabangs.id')
            ->join('petugas as p', 'p.id', '=', 'pc.petugas_id')
            ->join('users as u', 'u.email', '=', 'p.email')
            ->where('u.id', Auth::id()) // filter berdasarkan user login
            ->select('cabangs.*')
            ->distinct() // hindari duplikat data cabang
            ->get();

        $stokSampah = Sampah::with(['detailTransaksi', 'detailPengiriman'])
            ->get()
            ->map(function ($sampah) {
                $totalSetoran = $sampah->detailTransaksi->sum('berat_kg');
                $totalPengiriman = $sampah->detailPengiriman->sum('berat_kg');
                $sampah->stok = $totalSetoran - $totalPengiriman;
                return $sampah;
            });


        $gudang = Gudang::all();
        // dd($pengiriman->detail_pengiriman);
        $refUpladPengiriman = RefFilePengirimanPetugas::orderBy('urutan', 'ASC')->get();
        return view('pages.admin.pengiriman.edit', compact('pengiriman', 'cabang', 'gudang', 'stokSampah', 'refUpladPengiriman'));
    }
}

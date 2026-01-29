<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\JenisBadan;
use App\Models\Nasabah;
use App\Models\NasabahBadan;
use App\Models\SaldoPetugas;
use App\Models\User;
use App\Models\UserNasabah;
use App\Models\UserNasabahBadan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class NasabahUserBadanController extends Controller
{
    /**
     * Import data sampah dari file Excel/CSV (route: petugas.rekanan.sampah-import)
     */
    public function importSampah(Request $request, $nasabahBadanId)
    {
        $request->validate([
            'file_import' => 'required|file|mimes:csv,xlsx,xls',
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\SampahImport, $request->file('file_import'));
            return back()->with('success', 'Data sampah berhasil diimport.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $nasabahs = NasabahBadan::with('jenisBadan')
            ->when($request->search, function ($query) use ($request) {
                $query->where('nama_badan', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('username', 'like', '%' . $request->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('pages.petugas.nasabah-badan.index', compact('nasabahs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenisBadans = JenisBadan::all();
        return view('pages.petugas.nasabah-badan.create', compact('jenisBadans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_badan_id' => 'required|exists:jenis_badans,id',
            'nama_badan' => 'required|string|max:150',
            'npwp' => 'nullable|string|max:50|unique:nasabah_badan,npwp',
            'nib' => 'nullable|string|max:50|unique:nasabah_badan,nib',
            'email' => [
                'required',
                'email',
                'max:100',
                'unique:nasabah_badan,email',
                'unique:users,email',
            ],
            'username' => [
                'required',
                'string',
                'max:50',
                'unique:nasabah_badan,username',
                'unique:users,username',
            ],
            'password' => 'required|string|min:6',
            'no_telp' => 'nullable|string|max:20|unique:nasabah_badan,no_telp',
            'alamat_lengkap' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        $data = $request->all();
        // dd($data);
        $password = Hash::make($request->password);

        // Handle file upload
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/nasabah-badan', $filename);
            $data['foto'] = $filename;
        } else {
            $data['foto'] = 'profil.png';
        }

        $nasabah = new Nasabah();
        $nasabah->no_registrasi = 'REG-' . strtoupper(uniqid());
        $nasabah->status = 'aktif';
        $nasabah->type = 'badan';
        $nasabah->fill($data);
        $nasabah->save();

        // 1. Buat user di tabel users
        $user = User::create([
            'name' => $data['nama_badan'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => $password,
            'role' => 'nasabah_badan',
        ]);

        // Create UserNasabah
        $userNasabah = new UserNasabah();
        $userNasabah->user_id = $user->id;
        $userNasabah->nasabah_id = $nasabah->id;
        $userNasabah->save();

        // // 2. Buat nasabah badan
        // $nasabahBadan = NasabahBadan::create($data);

        // // 3. Simpan relasi ke tabel user_nasabah_badan
        // UserNasabahBadan::create([
        //     'user_id' => $user->id,
        //     'nasabah_badan_id' => $nasabahBadan->id,
        // ]);

        return redirect()
            ->route('petugas.data-rekanan.index')
            ->with('success', 'Nasabah Badan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $nasabahBadan = NasabahBadan::findOrFail($id);

        $nasabahBadan->load('jenisBadan');
        return view('pages.petugas.nasabah-badan.show', compact('nasabahBadan'));
    }

    /**
     * API endpoint to get single nasabah badan detail as JSON
     */
    public function apiShow($id)
    {
        $nasabahBadan = NasabahBadan::with('jenisBadan', 'userNasabahBadan.user', 'saldo')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $nasabahBadan
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $nasabahBadan = NasabahBadan::findOrFail($id);

        $jenisBadans = JenisBadan::all();
        return view('pages.petugas.nasabah-badan.edit', compact('nasabahBadan', 'jenisBadans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $nasabahBadan = NasabahBadan::findOrFail($id);

        // Get user related to this nasabah badan
        $userNasabahBadan = \App\Models\UserNasabahBadan::where('nasabah_badan_id', $nasabahBadan->id)->first();
        $userId = $userNasabahBadan ? $userNasabahBadan->user_id : null;

        $request->validate([
            'jenis_badan_id' => 'required|exists:jenis_badans,id',
            'nama_badan' => 'required|string|max:150',
            'npwp' => ['nullable', 'string', 'max:50', Rule::unique('nasabah_badan')->ignore($nasabahBadan->id)],
            'nib' => ['nullable', 'string', 'max:50', Rule::unique('nasabah_badan')->ignore($nasabahBadan->id)],
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('nasabah_badan')->ignore($nasabahBadan->id),
                Rule::unique('users')->ignore($userId),
            ],
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('nasabah_badan')->ignore($nasabahBadan->id),
                Rule::unique('users')->ignore($userId),
            ],
            'password' => 'nullable|string|min:6',
            'no_telp' => 'nullable|string|max:20',
            'alamat_lengkap' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        $data = $request->except('password');

        // Update password only if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle file upload
        if ($request->hasFile('foto')) {
            // Delete old file if exists
            if ($nasabahBadan->foto !== 'profil.png') {
                Storage::delete('public/nasabah-badan/' . $nasabahBadan->foto);
            }

            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/nasabah-badan', $filename);
            $data['foto'] = $filename;
        }

        // Update nasabah badan
        $nasabahBadan->update($data);

        // Update user table if user exists
        if ($userId) {
            $userData = [
                'name' => $request->nama_badan,
                'email' => $request->email,
                'username' => $request->username,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            \App\Models\User::where('id', $userId)->update($userData);
        }

        return redirect()
            ->route('petugas.rekanan.index')
            ->with('success', 'Nasabah Badan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NasabahBadan $nasabahBadan)
    {
        // Delete photo if not default
        if ($nasabahBadan->foto !== 'profil.png') {
            Storage::delete('public/nasabah-badan/' . $nasabahBadan->foto);
        }

        $nasabahBadan->delete();

        return redirect()
            ->route('petugas.rekanan.index')
            ->with('success', 'Nasabah Badan berhasil dihapus!');
    }

    /**
     * Show form setor sampah for nasabah badan
     */
    public function setorSampah($id)
    {
        $nasabahBadan = NasabahBadan::findOrFail($id);
        $kodeTransaksi = $this->generateUniqueTransactionCode();
        $stokSampah = \App\Models\Sampah::all();

        return view('pages.petugas.nasabah-badan.setor-sampah', compact('nasabahBadan', 'kodeTransaksi', 'stokSampah'));
    }

    /**
     * Store transaksi setor sampah for nasabah badan
     */
    public function storeSetorSampah(Request $request, $id)
    {
        $nasabahBadan = NasabahBadan::findOrFail($id);

        // Validasi input
        $request->validate([
            'kode_transaksi' => 'required|string|unique:transaksi_nasabah_badan,kode_transaksi',
            'tanggal_transaksi' => 'required|date',
            'detail_transaksi' => 'required|array|min:1',
            'detail_transaksi.*.sampah_id' => 'required|exists:sampah,id',
            'detail_transaksi.*.berat_kg' => 'required|numeric|min:0',
            'detail_transaksi.*.harga_per_kg' => 'required|numeric|min:0',
        ]);

        $totalTransaksi = 0;

        // Hitung total transaksi
        foreach ($request->detail_transaksi as $detail) {
            $hargaTotal = $detail['berat_kg'] * $detail['harga_per_kg'];
            $totalTransaksi += $hargaTotal;
        }

        // Cek saldo petugas
        $saldoPetugas = SaldoPetugas::join('petugas', 'saldo_petugas.petugas_id', '=', 'petugas.id')
            ->where('petugas.email', auth()->user()->email)
            ->select('saldo_petugas.*')
            ->first();

        if (!$saldoPetugas || $saldoPetugas->saldo < $totalTransaksi) {
            return back()->with('error', 'Saldo petugas tidak mencukupi untuk melakukan transaksi ini.');
        }

        // Ambil ID petugas
        $petugas = \App\Models\Petugas::where('email', auth()->user()->email)->first();
        $petugas_id = $petugas ? $petugas->id : null;

        // Simpan transaksi utama ke tabel transaksi_nasabah_badan
        $transaksi = \App\Models\TransaksiNasabahBadan::create([
            'kode_transaksi' => $request->kode_transaksi,
            'nasabah_badan_id' => $nasabahBadan->id,
            'petugas_id' => $petugas_id,
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'total_transaksi' => $totalTransaksi,
            'status' => 'selesai',
            'keterangan' => $request->keterangan ?? null,
        ]);

        // Simpan detail transaksi ke tabel detail_transaksi_nasabah_badan
        foreach ($request->detail_transaksi as $detail) {
            $hargaTotal = $detail['berat_kg'] * $detail['harga_per_kg'];

            \App\Models\DetailTransaksiNasabahBadan::create([
                'transaksi_nasabah_badan_id' => $transaksi->id,
                'sampah_id' => $detail['sampah_id'],
                'berat_kg' => $detail['berat_kg'],
                'harga_per_kg' => $detail['harga_per_kg'],
                'harga_total' => $hargaTotal,
            ]);
        }

        // Update saldo petugas (kurangi saldo)
        $sldPtgs = \App\Models\SaldoPetugas::where('id', $saldoPetugas->id)->first();
        $sldPtgs->saldo = $sldPtgs->saldo - $totalTransaksi;
        $sldPtgs->save();

        // Update saldo nasabah badan (jika ada tabel saldo untuk nasabah badan)
        // Cari atau buat saldo nasabah badan
        $saldoNasabahBadan = \App\Models\SaldoNasabahBadan::firstOrCreate(
            ['nasabah_badan_id' => $nasabahBadan->id],
            ['saldo' => 0]
        );

        // Tambah saldo nasabah badan
        $saldoNasabahBadan->saldo += $totalTransaksi;
        $saldoNasabahBadan->save();

        return redirect()
            ->route('petugas.rekanan.index')
            ->with('success', 'Transaksi setor sampah berhasil disimpan!');
    }

    /**
     * Generate unique transaction code khusus untuk TransaksiNasabahBadan
     */
    private function generateUniqueTransactionCode()
    {
        // Format: BSR-YYYYMMDD-BDN-001
        $today = now()->format('Ymd');
        $prefix = "BSR-{$today}-BDN-";

        $attempts = 0;
        $maxAttempts = 100;

        do {
            // Hitung jumlah transaksi hari ini + 1 untuk nomor urut
            $count = \App\Models\TransaksiNasabahBadan::where('kode_transaksi', 'like', $prefix . '%')
                ->count();

            $nextNumber = $count + 1;

            // Format nomor urut dengan leading zeros (minimal 3 digit)
            $formattedNumber = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            $kodeTransaksi = $prefix . $formattedNumber;

            // Cek apakah kode sudah ada (double check untuk race condition)
            $exists = \App\Models\TransaksiNasabahBadan::where('kode_transaksi', $kodeTransaksi)->exists();

            if (!$exists) {
                return $kodeTransaksi;
            }

            $attempts++;

            // Jika sudah banyak percobaan, tambahkan random string
            if ($attempts >= 10) {
                $randomNum = str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT);
                $kodeTransaksi = $prefix . $randomNum . '-' . strtoupper(substr(uniqid(), -4));
                $exists = \App\Models\TransaksiNasabahBadan::where('kode_transaksi', $kodeTransaksi)->exists();

                if (!$exists) {
                    return $kodeTransaksi;
                }
            }

            // Small delay to avoid tight loop
            usleep(10000); // 10ms

        } while ($attempts < $maxAttempts);

        // Fallback: gunakan timestamp + random untuk memastikan uniqueness
        return $prefix . time() . '-' . strtoupper(substr(uniqid(), -6));
    }

    /**
     * Show detail transaksi nasabah badan
     */
    public function showTransaksi($nasabahBadanId, $transaksiId)
    {
        $nasabahBadan = NasabahBadan::findOrFail($nasabahBadanId);
        $transaksi = \App\Models\TransaksiNasabahBadan::with([
            'nasabahBadan',
            'petugas',
            'detailTransaksi.sampah'
        ])->where('nasabah_badan_id', $nasabahBadanId)
            ->findOrFail($transaksiId);
        // dd($transaksi);
        return view('pages.petugas.nasabah-badan.transaksi-detail', compact('nasabahBadan', 'transaksi'));
    }

    /**
     * Cetak nota transaksi nasabah badan dalam format ringkas.
     */
    public function printTransaksi($nasabahBadanId, $transaksiId)
    {
        $nasabahBadan = NasabahBadan::findOrFail($nasabahBadanId);
        $transaksi = \App\Models\TransaksiNasabahBadan::with(['nasabahBadan', 'petugas', 'detailTransaksi.sampah'])
            ->where('nasabah_badan_id', $nasabahBadanId)
            ->findOrFail($transaksiId);

        return view('pages.petugas.nasabah-badan.transaksi-print', compact('nasabahBadan', 'transaksi'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapak;
use App\Models\Cabang;
use App\Models\Petugas;
use App\Models\TransaksiLapak;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LapakController extends Controller
{
    /**
     * Menampilkan daftar setoran lapak untuk approval
     */
    public function approvalSetoranLapak()
    {
        $lapaks = Lapak::with('cabang')
            ->where('approval_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('pages.admin.lapak.approval', compact('lapaks'));
    }

    public function approvalSetoranLapakDetail($code)
    {
        $transaksi = TransaksiLapak::with('lapak','jenisMetodePenarikan')->where('kode_transaksi', $code)->first();
        // dd($transaksi);
        if (!$transaksi) abort(404);
        $transaksi->detail_transaksi = DB::table('detail_transaksi_lapak')
            ->where('transaksi_lapak_id', $transaksi->id)
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
        return view('pages.admin.lapak.approvalSetoranLapakDetail', compact('transaksi'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Lapak::with('cabang');

        // Filter berdasarkan nama lapak
        if ($request->filled('nama_lapak')) {
            $query->where('nama_lapak', 'like', '%' . $request->input('nama_lapak') . '%');
        }

        // Filter berdasarkan status approval
        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->input('approval_status'));
        }

        // Filter berdasarkan cabang
        if ($request->filled('cabang_id')) {
            $query->where('cabang_id', $request->input('cabang_id'));
        }

        $lapaks = $query->orderBy('created_at', 'desc')->paginate(10);
        $cabangs = Cabang::where('status', 'aktif')->orderBy('nama_cabang')->get();

        return view('pages.admin.lapak.index', compact('lapaks', 'cabangs'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lapak = Lapak::with('cabang')->findOrFail($id);
        return view('pages.admin.lapak.show', compact('lapak'));
    }

    /**
     * Approve lapak
     */
    public function approve(string $id)
    {
        $lapak = Lapak::findOrFail($id);

        // Ambil petugas (admin) yang sedang login
        $petugas = Petugas::where('email', auth()->user()->email)->first();

        $lapak->update([
            'approval_status' => 'approved',
            'approved_by' => $petugas->id,
            'approved_at' => now(),
            'rejection_reason' => null,
            'status' => 'aktif' // Aktifkan lapak saat diapprove
        ]);

        Alert::success('Berhasil', 'Lapak berhasil disetujui');
        return redirect()->route('admin.lapak.index');
    }

    /**
     * Reject lapak
     */
    public function reject(Request $request, string $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ], [
            'rejection_reason.required' => 'Alasan penolakan harus diisi',
            'rejection_reason.max' => 'Alasan penolakan maksimal 500 karakter'
        ]);

        $lapak = Lapak::findOrFail($id);

        // Ambil petugas (admin) yang sedang login
        $petugas = Petugas::where('email', auth()->user()->email)->first();

        $lapak->update([
            'approval_status' => 'rejected',
            'approved_by' => $petugas->id,
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
            'status' => 'tidak_aktif' // Nonaktifkan lapak saat direject
        ]);

        Alert::success('Berhasil', 'Lapak berhasil ditolak');
        return redirect()->route('admin.lapak.index');
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
        return redirect()->route('admin.lapak.index');
    }
}

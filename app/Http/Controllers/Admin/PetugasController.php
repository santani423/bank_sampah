<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Petugas;
use App\Models\User;
use App\Models\saldoPetugas;
use App\Models\AlokasiSaldoAdmin;
use App\Models\petugasBalaceMutation;
use App\Models\SaldoUtama;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PetugasController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $page = max(1, (int) $request->get('page', 1));
        $petugas = Petugas::paginate($perPage, ['*'], 'page', $page);
        // dd($petugas->hasPages());
        return view('pages.admin.petugas.index', compact('petugas'));
    }

    public function create()
    {
        return view('pages.admin.petugas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:petugas',
            'username' => 'required|string|unique:petugas',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,petugas'
        ]);

        $pss = Hash::make($request->password);

        Petugas::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'username' => $request->username,
            'password' => $pss,
            'role' => $request->role,
        ]);

        DB::table('users')->insert([
            'name' => $request->nama,
            'email' => $request->email,
            'username' => $request->username,
            'password' => $pss,
            'role' => 'petugas',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.petugas.index')->with('success', 'Petugas berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $petugas = Petugas::with(['transaksi', 'saldoPetugas', 'alokasiDiterima.admin'])->findOrFail($id);
        
        // Get admin's balance - find by email karena Auth::user() menggunakan tabel users
        $adminUser = Auth::user();
        $adminPetugas = Petugas::where('email', $adminUser->email)->first();
        $adminSaldo = null;
        
        if ($adminPetugas) {
            $adminSaldo = saldoPetugas::where('petugas_id', $adminPetugas->id)->first();
        }
            // dd($adminPetugas->role);
        return view('pages.admin.petugas.show', compact('petugas', 'adminSaldo','adminPetugas'));
    }

    public function edit(string $id)
    {
        $petugas = Petugas::findOrFail($id);

        return view('pages.admin.petugas.edit', compact('petugas'));
    }



    public function update(Request $request, $id)
    {
        $petugas = Petugas::findOrFail($id);

        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:petugas,email,' . $petugas->id,
            'username' => 'required|string|unique:petugas,username,' . $petugas->id,
            'role' => 'required|in:admin,petugas',
            'password' => 'nullable|min:6' // password opsional
        ]);

        // Siapkan data yang akan diupdate
        $petugasData = [
            'nama' => $request->nama,
            'email' => $request->email,
            'username' => $request->username,
            'role' => $request->role,
        ];

        $userData = [
            'name' => $request->nama,
            'email' => $request->email,
            'username' => $request->username,
            'role' => $request->role,
        ];

        // Jika password diisi, hash dan tambahkan ke data update
        if (!empty($request->password)) {
            $hashedPassword = Hash::make($request->password);
            $petugasData['password'] = $hashedPassword;
            $userData['password'] = $hashedPassword;
        }

        // Update data pada tabel users
        $user = User::where('email', $petugas->email)->first();
        if ($user) {
            $user->update($userData);
        }

        // Update data pada tabel petugas
        $petugas->update($petugasData);

        return redirect()->route('admin.petugas.index')
            ->with('success', 'Petugas berhasil diperbarui.');
    }


    public function destroy($petugas)
    {

        Petugas::whereId($petugas)->delete();
        return redirect()->route('admin.petugas.index')->with('success', 'Petugas berhasil dihapus.');
    }

    public function alokasiSaldo(Request $request, $id)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        
        try {
            // Get admin - find by email karena Auth::user() menggunakan tabel users
            $adminUser = Auth::user();
            $admin = Petugas::where('email', $adminUser->email)->first();
            
            if (!$admin) {
                return back()->with('error', 'Data admin tidak ditemukan.');
            }
            
            $adminId = $admin->id;
            
            // Check if admin role
            if ($admin->role !== 'admin') {
                return back()->with('error', 'Hanya admin yang dapat melakukan alokasi saldo.');
            }
            
            // Get admin balance
            $adminSaldo = saldoPetugas::firstOrCreate(
                ['petugas_id' => $adminId],
                ['saldo' => 0]
            );
            
            // Validasi saldo admin minimal 1 juta
            if ($adminSaldo->saldo < 1000000) {
                return back()->with('error', 'Saldo admin kurang dari Rp 1.000.000. Tidak dapat melakukan alokasi.');
            }
            
            // Check if admin has enough balance
            if ($adminSaldo->saldo < $request->nominal) {
                return back()->with('error', 'Saldo admin tidak mencukupi untuk alokasi ini.');
            }
            
            // Get petugas
            $petugas = Petugas::findOrFail($id);
            
            // Get or create petugas balance
            $petugasSaldo = saldoPetugas::firstOrCreate(
                ['petugas_id' => $id],
                ['saldo' => 0]
            );
            
            // Record balances before transaction
            $saldoAdminSebelum = $adminSaldo->saldo;
            $saldoPetugasSebelum = $petugasSaldo->saldo;
            
            // Get saldo utama
            $saldoUtama = SaldoUtama::first();
            if (!$saldoUtama) {
                return back()->with('error', 'Saldo utama tidak ditemukan.');
            }
            
            // Check if saldo utama has enough balance
            if ($saldoUtama->saldo < $request->nominal) {
                return back()->with('error', 'Saldo utama tidak mencukupi untuk alokasi ini.');
            }
            
            $saldoUtamaSebelum = $saldoUtama->saldo;
            
            // Update balances
            $adminSaldo->saldo -= $request->nominal;
            $adminSaldo->save();
            
            $petugasSaldo->saldo += $request->nominal;
            $petugasSaldo->save();
            
            // Kurangi saldo utama
            $saldoUtama->saldo -= $request->nominal;
            $saldoUtama->keterangan = 'Alokasi saldo ke petugas ' . $petugas->nama . ' sebesar Rp ' . number_format($request->nominal, 0, ',', '.');
            $saldoUtama->save();
            
            // Record balances after transaction
            $saldoAdminSesudah = $adminSaldo->saldo;
            $saldoPetugasSesudah = $petugasSaldo->saldo;
            
            // Create allocation record
            AlokasiSaldoAdmin::create([
                'admin_id' => $adminId,
                'petugas_id' => $id,
                'nominal' => $request->nominal,
                'saldo_admin_sebelum' => $saldoAdminSebelum,
                'saldo_admin_sesudah' => $saldoAdminSesudah,
                'saldo_petugas_sebelum' => $saldoPetugasSebelum,
                'saldo_petugas_sesudah' => $saldoPetugasSesudah,
                'keterangan' => $request->keterangan,
            ]);
            
            // Create mutation record for admin (debit)
            petugasBalaceMutation::create([
                'petugas_id' => $adminId,
                'amount' => $request->nominal,
                'type' => 'debit',
                'source' => 'alokasi_ke_petugas',
                'description' => 'Alokasi saldo ke ' . $petugas->nama . '. ' . ($request->keterangan ?? ''),
            ]);
            
            // Create mutation record for petugas (credit)
            petugasBalaceMutation::create([
                'petugas_id' => $id,
                'amount' => $request->nominal,
                'type' => 'credit',
                'source' => 'alokasi_dari_admin',
                'description' => 'Alokasi saldo dari admin ' . $admin->nama . '. ' . ($request->keterangan ?? ''),
            ]);
            
            DB::commit();
            
            return back()->with('success', 'Saldo berhasil dialokasikan ke ' . $petugas->nama);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

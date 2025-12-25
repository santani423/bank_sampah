<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lapak extends Model
{
    use HasFactory;

    protected $table = 'lapak';

    protected $fillable = [
        'cabang_id',
        'kode_lapak',
        'nama_lapak',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'no_telepon',
        'deskripsi',
        'foto',
        'status',
        'approval_status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'jenis_metode_penarikan_id',
        'nama_rekening',
        'nomor_rekening'
    ];

    protected $casts = [
        'approved_at' => 'datetime'
    ];

    /* ================= RELATIONSHIP ================= */

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    public function jenisMetodePenarikan()
    {
        return $this->belongsTo(JenisMetodePenarikan::class, 'jenis_metode_penarikan_id');
    }

    /**
     * Cabang -> Gudangs (melalui cabang)
     */
    public function gudangs()
    {
        return $this->hasManyThrough(
            Gudang::class,
            Cabang::class,
            'id',         // FK di cabang
            'cabang_id',  // FK di gudang
            'cabang_id',  // FK di lapak
            'id'          // PK di cabang
        );
    }

    /* ================= RELATIONSHIP ================= */

    public function transaksiLapak()
    {
        return $this->hasMany(TransaksiLapak::class, 'lapak_id');
    }

    /**
     * Ambil transaksi lapak dengan approval pending
     */
    public function transaksiPending()
    {
        return $this->transaksiLapak()
            ->where('approval', 'pending')
            ->where('status_transaksi', 'pending');
    }
}

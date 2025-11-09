<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiNasabahBadan extends Model
{
    use HasFactory;

    protected $table = 'transaksi_nasabah_badan';

    protected $fillable = [
        'kode_transaksi',
        'nasabah_badan_id',
        'petugas_id',
        'tanggal_transaksi',
        'total_transaksi',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'total_transaksi' => 'decimal:2',
    ];

    /**
     * Relasi ke NasabahBadan
     */
    public function nasabahBadan()
    {
        return $this->belongsTo(NasabahBadan::class, 'nasabah_badan_id');
    }

    /**
     * Relasi ke Petugas
     */
    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'petugas_id');
    }

    /**
     * Relasi ke DetailTransaksiNasabahBadan
     */
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksiNasabahBadan::class, 'transaksi_nasabah_badan_id');
    }

    /**
     * Relasi untuk menarik data Sampah yang terlibat dalam transaksi
     * melalui tabel pivot detail_transaksi_nasabah_badan beserta atribut pivot-nya.
     */
    public function sampah()
    {
        return $this->belongsToMany(
            Sampah::class,
            'detail_transaksi_nasabah_badan',
            'transaksi_nasabah_badan_id',
            'sampah_id'
        )->withPivot(['berat_kg', 'harga_per_kg', 'harga_total']);
    }
}

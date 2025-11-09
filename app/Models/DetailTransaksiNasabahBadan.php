<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksiNasabahBadan extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksi_nasabah_badan';

    // Otomatis ikutkan data sampah saat memuat detail transaksi
    protected $with = ['sampah'];

    protected $fillable = [
        'transaksi_nasabah_badan_id',
        'sampah_id',
        'berat_kg',
        'harga_per_kg',
        'harga_total',
    ];

    protected $casts = [
        'berat_kg' => 'decimal:2',
        'harga_per_kg' => 'decimal:2',
        'harga_total' => 'decimal:2',
    ];

    /**
     * Relasi ke TransaksiNasabahBadan
     */
    public function transaksiNasabahBadan()
    {
        return $this->belongsTo(TransaksiNasabahBadan::class, 'transaksi_nasabah_badan_id');
    }

    /**
     * Relasi ke Sampah
     */
    public function sampah()
    {
        return $this->belongsTo(Sampah::class, 'sampah_id');
    }
}

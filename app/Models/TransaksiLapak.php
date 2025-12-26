<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiLapak extends Model
{
    use HasFactory;

    protected $table = 'transaksi_lapak';
    protected $guarded = [];

    /**
     * Relasi ke tabel lapak
     */
    public function lapak()
    {
        return $this->belongsTo(Lapak::class, 'lapak_id');
    }

    /**
     * Relasi ke jenis metode penarikan melalui Lapak
     * (transaksi_lapak -> lapak -> jenis_metode_penarikans)
     */
    public function jenisMetodePenarikan()
    {
        return $this->hasOneThrough(
            JenisMetodePenarikan::class,  // model tujuan
            Lapak::class,                 // model perantara
            'id',                         // foreign key di Lapak
            'id',                         // foreign key di JenisMetodePenarikan
            'lapak_id',                   // foreign key di TransaksiLapak
            'jenis_metode_penarikan_id'   // foreign key di Lapak
        );
    }

    /**
     * Mendapatkan data detail pengiriman lapak terkait.
     */
    public function detailTransaksiLapak()
    {
        return $this->hasMany(
            DetailTransaksiLapak::class,
            'transaksi_lapak_id'
        );
    }
}

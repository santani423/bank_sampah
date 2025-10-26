<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class PengirimanPetugas extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = ['id'];

    /**
     * Relasi ke tabel detail_pengiriman
     * Setiap pengiriman_petugas memiliki banyak detail pengiriman
     */
    public function detailPengiriman()
    {
        return $this->hasMany(DetailPengiriman::class, 'pengiriman_id');
    }

    /**
     * Relasi ke tabel cabangs
     * Setiap pengiriman petugas dimiliki oleh satu cabang
     */
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    /**
     * Relasi ke tabel gudangs
     * Setiap pengiriman petugas dilakukan ke satu gudang
     */
    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }


    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'petugas_id');
    }

    /**
     * Relasi ke tabel file_pengiriman_petugas
     * Setiap pengiriman petugas bisa memiliki banyak file
     */
    public function files()
    {
        return $this->hasMany(FilePengirimanPetugas::class, 'pengiriman_petugas_id');
    }
}

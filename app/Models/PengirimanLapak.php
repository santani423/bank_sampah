<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class PengirimanLapak extends Model
{
    use HasFactory, LogsActivity;

    /**
     * Mendapatkan data detail pengiriman lapak terkait.
     */
    public function detailPengirimanLapaks()
    {
        return $this->hasMany(
            DetailPengirimanLapak::class,
            'pengiriman_lapak_id'
        );
    }

    /**
     * Mendapatkan data gudang terkait pengiriman lapak.
     */
    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }
   



    /**
     * Mendapatkan data lapak terkait pengiriman lapak.
     */
    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'petugas_id');
    }
}

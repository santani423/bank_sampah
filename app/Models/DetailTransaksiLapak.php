<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksiLapak extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksi_lapak';

    protected $guarded = ['id'];

    /**
     * Relasi ke model Sampah
     */
    public function sampah()
    {
        return $this->belongsTo(Sampah::class, 'sampah_id');
    }
}

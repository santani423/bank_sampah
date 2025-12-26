<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class DetailPengirimanLapak extends Model
{
    use HasFactory, LogsActivity;

    public function transaksiLapak()
    {
        return $this->belongsTo(TransaksiLapak::class, 'transaksi_lapak_id');
    }
}

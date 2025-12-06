<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiLapak extends Model
{
    use HasFactory;
    protected $table = 'transaksi_lapak';
    protected $guarded = [];



    public function lapak()
    {
        return $this->belongsTo(Lapak::class, 'lapak_id');
    }
}

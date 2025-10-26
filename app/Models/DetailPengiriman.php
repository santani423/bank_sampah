<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;  

class DetailPengiriman extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'detail_pengiriman';

    protected   $guarded = ['id'];

    public function pengiriman()
    {
        return $this->belongsTo(PengirimanPetugas::class, 'pengiriman_id');
    }

    public function sampah()
    {
        return $this->belongsTo(Sampah::class, 'sampah_id');
    }
}

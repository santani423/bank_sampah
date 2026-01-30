<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class PencairanLapak extends Model
{
    use HasFactory, LogsActivity;

    /**
     * Mass assignment protection
     * Lebih aman gunakan guarded daripada fillable
     */
    protected $guarded = ['id'];

    /**
     * Cast tipe data (opsional tapi direkomendasikan)
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke PengirimanLapak
     * pencairan_lapaks.pengiriman_lapak_id → pengiriman_lapaks.id
     */
    public function pengirimanLapak()
    {
        return $this->belongsTo(
            PengirimanLapak::class,
            'pengiriman_lapak_id',     // kolom di pencairan_lapaks
            'id'          // kolom di pengiriman_lapaks
        );
    }


    public function lapak()
    {
        return $this->belongsTo(
            Lapak::class,
            'lapak_id',     // kolom di pencairan_lapaks
            'id'          // kolom di lapaks
        );
    }
}

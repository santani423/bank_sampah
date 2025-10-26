<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class PencairanSaldo extends Model
{
    use HasFactory, LogsActivity;

    protected   $guarded = ['id'];

    protected $table = 'pencairan_saldo';

    protected $fillable = [
        'nasabah_id',
        'metode_id',
        'jumlah_pencairan',
        'tanggal_pengajuan',
        'tanggal_proses',
        'status',
        'keterangan'
    ];

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class, 'nasabah_id');
    }

    public function metodePencairan()
    {
        return $this->belongsTo(MetodePencairan::class, 'metode_id');
    }

    public function metode()
    {
        return $this->belongsTo(MetodePencairan::class, 'metode_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class AlokasiSaldoAdmin extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'alokasi_saldo_admin';

    protected $fillable = [
        'admin_id',
        'petugas_id',
        'nominal',
        'saldo_admin_sebelum',
        'saldo_admin_sesudah',
        'saldo_petugas_sebelum',
        'saldo_petugas_sesudah',
        'keterangan',
    ];

    // Relasi ke admin
    public function admin()
    {
        return $this->belongsTo(Petugas::class, 'admin_id');
    }

    // Relasi ke petugas
    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'petugas_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\LogsActivity; 

class Petugas extends Authenticatable
{
    use HasFactory, LogsActivity; 

    protected $table = 'petugas';

    protected $fillable = [
        'nama', 'email', 'username', 'password', 'role'
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'petugas_id');
    }

    // Relasi ke saldo petugas
    public function saldoPetugas()
    {
        return $this->hasOne(saldoPetugas::class, 'petugas_id');
    }

    // Relasi ke alokasi yang diterima
    public function alokasiDiterima()
    {
        return $this->hasMany(AlokasiSaldoAdmin::class, 'petugas_id');
    }

    // Relasi ke alokasi yang diberikan (untuk admin)
    public function alokasiDiberikan()
    {
        return $this->hasMany(AlokasiSaldoAdmin::class, 'admin_id');
    }

    // Relasi ke cabang melalui tabel pivot petugas_cabangs
    public function cabangs()
    {
        return $this->belongsToMany(\App\Models\cabang::class, 'petugas_cabangs', 'petugas_id', 'cabang_id');
    }

}

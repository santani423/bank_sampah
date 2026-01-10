<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Cabang extends Model
{
    use HasFactory, LogsActivity;
    protected $guarded = [];

    /**
     * Relasi ke tabel lapak
     * Satu cabang bisa memiliki banyak lapak
     */
    public function lapaks()
    {
        return $this->hasMany(Lapak::class, 'cabang_id');
    }

    /**
     * Relasi ke tabel petugas melalui petugas_cabangs
     * Satu cabang bisa memiliki banyak petugas
     */
    public function petugas()
    {
        return $this->belongsToMany(Petugas::class, 'petugas_cabangs', 'cabang_id', 'petugas_id');
    }
}

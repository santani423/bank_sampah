<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lapak extends Model
{
    use HasFactory;

    protected $table = 'lapak';

    protected $fillable = [
        'cabang_id',
        'kode_lapak',
        'nama_lapak',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'no_telepon',
        'deskripsi',
        'foto',
        'status'
    ];

    /**
     * Relasi ke tabel cabang
     * Satu lapak dimiliki oleh satu cabang
     */
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }
}

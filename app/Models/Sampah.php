<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sampah extends Model
{
    use HasFactory;

    protected $table = 'sampah';

    protected $fillable = [
        'nama_sampah',
        'harga_per_kg',
        'gambar'
    ];

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'sampah_id');
    }

    public function detailPengiriman()
    {
        return $this->hasMany(DetailPengiriman::class, 'sampah_id');
    }
}

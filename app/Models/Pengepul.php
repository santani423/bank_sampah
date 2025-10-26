<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;  

class Pengepul extends Model
{
    use HasFactory, LogsActivity; 

    protected $table = 'pengepul';

    protected $fillable = [
        'nama', 'alamat', 'kontak'
    ];

    public function pengiriman()
    {
        return $this->hasMany(PengirimanPengepul::class, 'pengepul_id');
    }

}

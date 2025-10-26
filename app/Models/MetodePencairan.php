<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;  

class MetodePencairan extends Model
{
     use HasFactory, LogsActivity;

    protected $table = 'metode_pencairan';

    protected $fillable = [
        'nasabah_id', 'nama_metode_pencairan', 'no_rek'
    ];

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class, 'nasabah_id');
    }

    public function jenisMetodePenarikan()
    {
        return $this->belongsTo(JenisMetodePenarikan::class, 'jenis_metode_penarikan_id');
    }

}

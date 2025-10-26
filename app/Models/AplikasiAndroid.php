<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity; 

class AplikasiAndroid extends Model
{
    use HasFactory, LogsActivity; 

    protected $table = 'aplikasi_android';

    protected $fillable = [
        'versi_aplikasi',
        'nama_file',
        'ukuran_file',
        'url_apk',
        'keterangan'
    ];
}

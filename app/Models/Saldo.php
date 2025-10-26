<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;  

class Saldo extends Model
{
      use HasFactory, LogsActivity; 
      
    protected $table = 'saldo';

    protected $fillable = [
        'nasabah_id', 'saldo', 'tanggal_update'
    ];

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;  

class Time extends Model
{
    use HasFactory, LogsActivity; 

    // protected $table = 'times';

    protected $fillable = [
        'name',
        'avatar',
        'jabatan',
        'keterangan',
    ];
}

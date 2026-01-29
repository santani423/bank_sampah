<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;  

class CabangUser extends Model
{
    use HasFactory, LogsActivity; 

    protected $fillable = [
        'cabang_id', 'user_nasabah_id'
    ];
    
}   

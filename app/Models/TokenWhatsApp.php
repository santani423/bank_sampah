<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;  

class TokenWhatsApp extends Model
{
     use HasFactory, LogsActivity; 

    protected $table = 'token_whatsapp';

    protected $fillable = [
        'token_whatsapp'
    ];

}

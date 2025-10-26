<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;  

class Label extends Model
{
    use HasFactory, LogsActivity; // gunakan trait LogsActivity

    protected $fillable = [
        'name',
        'slug',
        'color',
        'description',
    ];
}

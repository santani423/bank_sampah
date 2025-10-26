<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;  

class Banner extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'banner';

    protected $fillable = [
        'nama_banner', 'file_banner'
    ];

}

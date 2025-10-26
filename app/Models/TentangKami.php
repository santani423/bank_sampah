<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;  

class TentangKami extends Model
{
    use HasFactory, LogsActivity; 

    protected $table = 'tentang_kami';

    protected $fillable = [
        'isi_tentang_kami',
    ];
}

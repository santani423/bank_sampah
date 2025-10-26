<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;  

class Feedback extends Model
{
    use HasFactory, LogsActivity; 

    protected $table = 'feedback';

    protected $fillable = [
        'judul_feedback', 'isi_feedback', 'nasabah_id'
    ];

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class, 'nasabah_id');
    }

}

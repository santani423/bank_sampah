<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNasabahBadan extends Model
{
    use HasFactory;
    protected $table = 'user_nasabah_badan';
    protected $fillable = [
        'user_id',
        'nasabah_badan_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function nasabahBadan()
    {
        return $this->belongsTo(NasabahBadan::class);
    }
}

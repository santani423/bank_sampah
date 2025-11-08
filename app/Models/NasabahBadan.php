<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class NasabahBadan extends Model
{
    protected $table = 'nasabah_badan';
    use HasFactory, LogsActivity;
    protected $guarded = [];
    /**
     * Get the jenis badan associated with the nasabah badan.
     */
    public function jenisBadan()
    {
        return $this->belongsTo(JenisBadan::class, 'jenis_badan_id');
    }
}

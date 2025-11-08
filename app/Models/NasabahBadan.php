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

    /**
     * Get the user through user_nasabah_badan pivot table.
     */
    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            UserNasabahBadan::class,
            'nasabah_badan_id', // Foreign key on user_nasabah_badan table
            'id', // Foreign key on users table
            'id', // Local key on nasabah_badan table
            'user_id' // Local key on user_nasabah_badan table
        );
    }

    /**
     * Get the user_nasabah_badan relationship.
     */
    public function userNasabahBadan()
    {
        return $this->hasOne(UserNasabahBadan::class, 'nasabah_badan_id');
    }
}

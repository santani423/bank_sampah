<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logging extends Model
{
    use HasFactory;

    protected $table = 'logging';

    protected $fillable = [
        'code', 'user_id', 'action', 'description',
        'data_before', 'data_after', 'ip_address', 'user_agent',
    ];

    protected $casts = [
        'data_before' => 'array',
        'data_after' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Auto-generate kode unik saat create
    protected static function booted()
    {
        static::creating(function ($logging) {
            $last = self::latest('id')->first();
            $number = $last ? $last->id + 1 : 1;
            $logging->code = 'LOG-' . str_pad($number, 5, '0', STR_PAD_LEFT);
        });
    }
}

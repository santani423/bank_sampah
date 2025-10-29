<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Logging extends Model
{
    use HasFactory;

    protected $table = 'logging';

    protected $fillable = [
        'code',
        'user_id',
        'action',
        'description',
        'data_before',
        'data_after',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'data_before' => 'array',
        'data_after' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Auto-generate kode unik saat membuat log baru
    protected static function booted()
    {
        static::creating(function ($logging) {
            do {
                // contoh hasil: LOG-AB123
                $code = 'LOG-' . strtoupper(Str::random(50));
            } while (self::where('code', $code)->exists());

            $logging->code = $code;
        });
    }
}

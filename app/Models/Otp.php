<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class Otp extends Model
{
    use HasFactory;

    protected $table = 'otps';

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'identifier',   // no_hp / email
        'otp_hash',
        'type',         // register, login, reset_password
        'is_used',
        'expired_at',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'is_used'    => 'boolean',
        'expired_at' => 'datetime',
    ];

    /* =====================================================
     |  Helper & Business Logic Methods
     |=====================================================*/

    /**
     * Generate & store OTP (hashed)
     */
    public static function generate(
        string $identifier,
        string $type = 'verification',
        int $ttlMinutes = 5
    ): array {
        $otp = random_int(100000, 999999);

        $record = self::create([
            'identifier' => $identifier,
            'otp_hash'   => Hash::make($otp),
            'type'       => $type,
            'expired_at' => now()->addMinutes($ttlMinutes),
        ]);

        return [
            'otp'   => $otp,     // hanya untuk dikirim ke user
            'model' => $record,  // record DB
        ];
    }

    /**
     * Verify OTP
     */
    public static function verify(
        string $identifier,
        string $otp,
        string $type = 'verification'
    ): bool {
        $record = self::where('identifier', $identifier)
            ->where('type', $type)
            ->where('is_used', false)
            ->where('expired_at', '>', now())
            ->latest()
            ->first();

        if (!$record || !Hash::check($otp, $record->otp_hash)) {
            return false;
        }

        $record->update(['is_used' => true]);

        return true;
    }

    /**
     * Scope: only active OTP
     */
    public function scopeActive($query)
    {
        return $query
            ->where('is_used', false)
            ->where('expired_at', '>', now());
    }

    /**
     * Check if OTP is expired
     */
    public function isExpired(): bool
    {
        return Carbon::now()->greaterThan($this->expired_at);
    }
}

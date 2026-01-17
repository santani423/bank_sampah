<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OtpVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_number',
        'otp_code',
        'type',
        'is_verified',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_verified' => 'boolean'
    ];

    /**
     * Generate OTP code
     */
    public static function generateOTP($phoneNumber, $type = 'registration')
    {
        // Generate 6 digit OTP
        $otpCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Delete old OTP for this phone number and type
        self::where('phone_number', $phoneNumber)
            ->where('type', $type)
            ->where('is_verified', false)
            ->delete();
        
        // Create new OTP
        return self::create([
            'phone_number' => $phoneNumber,
            'otp_code' => $otpCode,
            'type' => $type,
            'expires_at' => Carbon::now()->addMinutes(5) // OTP expires in 5 minutes
        ]);
    }

    /**
     * Verify OTP
     */
    public static function verifyOTP($phoneNumber, $otpCode, $type = 'registration')
    {
        $otp = self::where('phone_number', $phoneNumber)
            ->where('otp_code', $otpCode)
            ->where('type', $type)
            ->where('is_verified', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();
        
        if ($otp) {
            $otp->is_verified = true;
            $otp->save();
            return true;
        }
        
        return false;
    }

    /**
     * Check if OTP is expired
     */
    public function isExpired()
    {
        return $this->expires_at < Carbon::now();
    }
}

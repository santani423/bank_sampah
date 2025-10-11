<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNasabah extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Relasi ke tabel users
     * Setiap UserNasabah dimiliki oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke tabel nasabah (jika ada model Nasabah)
     */
    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class, 'nasabah_id');
    }

    /**
     * Fungsi untuk mendapatkan data user berdasarkan user_nasabah.id
     */
    public static function getUserByUserNasabahId($id)
    {
        return self::with('user')->find($id)?->user;
    }

    /**
     * Fungsi untuk mendapatkan semua user yang terhubung dengan nasabah
     */
    public static function getAllUsers()
    {
        return self::with('user')->get()->pluck('user');
    }
  
    /**
     * Fungsi untuk mendapatkan data nasabah berdasarkan user_nasabah.id
     */
    public static function getNasabahByUserNasabahId($id)
    {
        return self::with('nasabah')->find($id)?->nasabah;
    }

    /**
     * Fungsi untuk mendapatkan semua nasabah beserta user-nya
     */
    public static function getAllNasabahWithUser()
    {
        return self::with(['nasabah', 'user'])->get();
    }
}

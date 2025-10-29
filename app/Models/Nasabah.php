<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Nasabah extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'nasabah';

    protected $fillable = [
        'no_registrasi',
        'nik',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'no_hp',
        'email',
        'username',
        'password',
        'alamat_lengkap',
        'foto',
        'status'
    ];

    public function saldo()
    {
        return $this->hasOne(Saldo::class, 'nasabah_id');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'nasabah_id');
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'nasabah_id');
    }

    public function metodePencairan()
    {
        return $this->hasMany(MetodePencairan::class, 'nasabah_id');
    }

    public function pencairanSaldo()
    {
        return $this->hasMany(PencairanSaldo::class, 'nasabah_id');
    }

    /**
     * Relasi ke tabel user_nasabahs
     * Satu nasabah bisa memiliki satu user
     */
    public function userNasabah()
    {
        return $this->hasOne(UserNasabah::class, 'nasabah_id');
    }

    /**
     * Relasi langsung ke tabel users melalui user_nasabahs
     */
    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            UserNasabah::class,
            'nasabah_id', // Foreign key di tabel user_nasabahs
            'id',         // Foreign key di tabel users
            'id',         // Local key di tabel nasabahs
            'user_id'     // Local key di tabel user_nasabahs
        );
    }

    /**
     * Relasi ke tabel logging melalui user
     * Satu nasabah bisa memiliki banyak log
     */
    public function loggings()
    {
        return $this->hasManyThrough(
            Logging::class,       // Model akhir
            UserNasabah::class,   // Model perantara
            'nasabah_id',         // Foreign key di user_nasabahs
            'user_id',            // Foreign key di logging
            'id',                 // Local key di nasabah
            'user_id'             // Local key di user_nasabahs
        );
    }

    /**
     * Ambil log terbaru dari nasabah
     */
    public function latestLog()
    {
        return $this->loggings()->latest()->first();
    }
}

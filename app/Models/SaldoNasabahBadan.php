<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoNasabahBadan extends Model
{
    use HasFactory;

    protected $table = 'saldo_nasabah_badans';

    protected $fillable = [
        'nasabah_badan_id',
        'saldo',
    ];

    protected $casts = [
        'saldo' => 'decimal:2',
    ];

    /**
     * Relasi ke NasabahBadan
     */
    public function nasabahBadan()
    {
        return $this->belongsTo(NasabahBadan::class, 'nasabah_badan_id');
    }
}

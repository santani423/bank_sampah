<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoUtama extends Model
{
    use HasFactory;

    protected $table = 'saldo_utama';

    protected $fillable = [
        'saldo',
        'keterangan'
    ];

    protected $casts = [
        'saldo' => 'decimal:2'
    ];
}

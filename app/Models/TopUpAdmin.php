<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopUpAdmin extends Model
{
    use HasFactory;

    protected $table = 'top_up_admin';

    protected $fillable = [
        'user_id',
        'jumlah',
        'metode_pembayaran',
        'status',
        'xendit_invoice_id',
        'xendit_invoice_url',
        'xendit_external_id',
        'keterangan',
        'tanggal_bayar'
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal_bayar' => 'datetime'
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

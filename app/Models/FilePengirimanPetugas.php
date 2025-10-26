<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class FilePengirimanPetugas extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = ['id'];

    /**
     * Setiap kali model ini dipanggil, otomatis eager load refFile
     */
    protected $with = ['refFile'];

    /**
     * Relasi ke pengiriman petugas
     * Setiap file dimiliki oleh satu pengiriman petugas
     */
    public function pengiriman()
    {
        return $this->belongsTo(PengirimanPetugas::class, 'pengiriman_petugas_id');
    }

    /**
     * Relasi ke referensi file pengiriman
     */
    public function refFile()
    {
        return $this->belongsTo(RefFilePengirimanPetugas::class, 'ref_file_id');
    }

    /**
     * Relasi ke user yang meng-upload file
     */
    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}

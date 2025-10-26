<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\LogsActivity;  

class Clean extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'title',
        'slug',
        'description',   
        'image',
        'status', 
    ];

     

    /**
     * Auto generate slug jika belum ada
     */
    protected static function booted()
    {
        static::creating(function ($clean) {
            if (empty($clean->slug)) {
                $clean->slug = Str::slug($clean->title . '-' . Str::random(5));
            }
        });
    }
}

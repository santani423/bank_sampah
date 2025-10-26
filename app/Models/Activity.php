<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'activities';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'start_date',
        'end_date',
        'location',
        'image',
        'status',
        'label_id', // tambahkan label_id
    ];

    // Auto-generate slug dari title
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Relasi ke label
    public function label()
    {
        return $this->belongsTo(Label::class);
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CleanResource extends JsonResource
{
    public function toArray($request)
    {
        $baseUrl = url('/'); // otomatis pakai APP_URL dari .env

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => $this->image ? asset('storage/' .$this->image) : null,
            'status' => $this->status,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}

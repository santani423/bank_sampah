<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'slug'        => $this->slug,
            'description' => $this->description,
            'content'     => $this->content,
            'start_date'  => $this->start_date,
            'end_date'    => $this->end_date,
            'location'    => $this->location,
            'image'       => $this->image ? asset($this->image) : null,
            'status'      => $this->status,
            'label_id'    => $this->label_id,
            'label'       => $this->label ? $this->label->name : null,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}

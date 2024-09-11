<?php

namespace App\Http\Resources\Tour;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TourImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'position'   => $this->position,
            'image'      => $this->imageUrl('image'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

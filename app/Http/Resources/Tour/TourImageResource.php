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
            'path'       => $this->path,
        ];
    }
}

<?php

namespace App\Http\Resources\Tour;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Location\LocationResource;

class TourResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'slug'          => $this->slug,
            'max_person'    => $this->max_person,
            'is_active'     => $this->is_active,

            'price'     => $this->when(
                $this->price != null,
                $this->price
            ),

            'description' => $this->whenLoaded(
                'translations',
                fn() => $this->translations->where('field','description')->first()->translation
            ),

            'meta' => $this->whenLoaded(
                'translations',
                fn() => $this->translations->where('field','meta')->first()->translation
            ),

            'images'          => TourImageResource::collection($this->whenLoaded('images')),
            'thumbnail_image' => new TourImageResource($this->whenLoaded('thumbnailImage')),
            'locations'       => LocationResource::collection($this->whenLoaded('locations')),
            'variants'        => TourVariantResource::collection($this->whenLoaded('variants')),
        ];
    }
}

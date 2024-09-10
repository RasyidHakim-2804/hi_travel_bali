<?php

namespace App\Http\Resources\Car;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Facility\ListFacilityResource;

class CarResource extends JsonResource
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
            'price'         => $this->price,
            'is_active'     => $this->is_active,
            'max_person'    => $this->max_person,
            'image'         => $this->imageUrl('image'),
            
            $this->mergeWhen(($this->price_per_km != null), [
                'price_per_km'  => $this->price_per_km,
            ]),

            'facilities'    => ListFacilityResource::collection($this->whenLoaded('facilities')),
        ];
    }
}

<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Facility\FacilityResource;
use App\Http\Resources\Location\LocationResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'slug'          => $this->slug,
            'price'         => $this->idr_price,
            'max_person'    => $this->max_person,
            'is_active'     => $this->is_active,
            
            $this->mergeWhen(($this->type == Product::TRANSPORT_TYPE), [
                'price_per_km'  => $this->price_per_km,
            ]),

            $this->mergeWhen($this->trip_price, [
                'trip_price'    => $this->trip_price
            ]),

            $this->mergeWhen($this->relationLoaded('translations'), function() {
                $translations = [];

                foreach ($this->translations as $translation) {
                    $translations[$translation->lang . '_description'] = $translation->description; 
                }

                return $translations;
            }),

            $this->mergeWhen($this->relationLoaded('translate'), function() {
                return [
                    'description'   => $this->translate->description,
                ];
            }),

            'images'        => ProductImageResource::collection($this->whenLoaded('images')),

            'image'         => new ProductImageResource($this->whenLoaded('thumbnailImage')),

            'facilities'    => FacilityResource::collection($this->whenLoaded('facilities')),

            'locations'     => LocationResource::collection($this->whenLoaded('locations')),
        ];
    }
}

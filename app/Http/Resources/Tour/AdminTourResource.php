<?php

namespace App\Http\Resources\Tour;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Location\LocationResource;

class AdminTourResource extends JsonResource
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
            'price'         => $this->whenLoaded(
                'defaultVariant',
                fn() => $this->defaultVariant->price,
                $this->price
            ),
            'max_person'    => $this->max_person,
            'is_active'     => $this->is_active,
            'is_varied'     => $this->is_varied,

            'description' => $this->whenLoaded(
                'translations',
                fn() => TourTranslationResource::collection(
                    $this->translations->where('field','description')
                )
            ),

            'variants'      => AdminTourVariantResource::collection($this->whenLoaded('variants')),

            'locations'     => LocationResource::collection($this->whenLoaded('locations')),
        ];
    }
}

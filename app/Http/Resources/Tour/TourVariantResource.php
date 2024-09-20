<?php

namespace App\Http\Resources\Tour;

use Illuminate\Http\Resources\Json\JsonResource;

class TourVariantResource extends JsonResource
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
            'tour_id'       => $this->tour_id,
            'is_default'    => $this->is_default,
            'price'         => $this->price,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,

            'description' => $this->whenLoaded(
                'translations',
                fn() => TourVariantTranslationResource::collection(
                    $this->translations->where('field','description')
                )
            ),
        ];
    }
}

<?php

namespace App\Http\Resources\Api\V1\Facility;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FacilityResource extends JsonResource
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
            'icon_url'      => $this->icon_url,
            $this->mergeWhen($this->relationLoaded('translations'), function() {
                $translations = [];

                foreach ($this->translations as $translation) {
                    $translations[$translation->lang . '_description'] = $translation->description;
                }

                return $translations;
            }),
            'created_at'    => $this->creatde_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}

<?php

namespace App\Models;

use App\Traits\ImageStorageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Facility extends Model
{
    use HasFactory, ImageStorageTrait;

    protected $imageSetting = [
        'folder' => 'facility',
        'field'  => [
            'image',
        ],
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(FacilityTranslation::class);
    }

    public function singleTranslations(): HasOne
    {
        return $this->hasOne(FacilityTranslation::class);
    }

    public function cars(): BelongsToMany
    {
        return $this->belongsToMany(Car::class, 'pivot_car_facility', 'facility_id', 'car_id');
    }
}

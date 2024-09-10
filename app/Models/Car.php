<?php

namespace App\Models;

use App\Traits\ImageStorageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Car extends Model
{
    use HasFactory, ImageStorageTrait, HasSlug, SoftDeletes;

    protected $imageSetting = [
        'folder' => 'car',
        'field'  => [
            'image',
        ],
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
                ->generateSlugsFrom('name')
                ->saveSlugsTo('slug');
    }

    public function driver(): BelongsToMany
    {
        return $this->belongsToMany(Driver::class, PivotDriverCar::class, 'car_id', 'driver_id');
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class, 'pivot_car_facility');
    }

    public function scopeActive($query): void
    {
        $query->where('is_active', true);
    }

    public function scopeTripPrice($query, float $distance): void
    {
        $result = round($distance);

        $query->select(
            '*',
            DB::raw('price + (price_per_km * ' . $result . ') as trip_price')
        );
    }
}

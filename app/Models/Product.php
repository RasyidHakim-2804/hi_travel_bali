<?php

namespace App\Models;

use App\Models\Image\ProductImage;
use App\Models\Translation\ProductTranslation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasFactory, HasSlug;

    public const TRANSPORT_TYPE   = 'transport';

    public const TOUR_TYPE        = 'tour';

    public function scopeTransport(Builder $query): void
    {
        $query->where('type', '=', static::TRANSPORT_TYPE);
    }

    public function scopeTour(Builder $query): void
    {
        $query->where('type', '=', static::TOUR_TYPE);
    }

    public function scopeTripPrice(Builder $query, float $distance): void
    {
        $result = floor($distance);
        $gap    = $distance - $result;

        if ($gap > 0.5) {
            $result = ceil($distance);
        }

        $query->select(
            '*',
            DB::raw('idr_price + (price_per_km * ' . $result . ') as trip_price')
        );
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
                ->generateSlugsFrom('name')
                ->saveSlugsTo('slug')
                ->startSlugSuffixFrom('2')
                ->slugsShouldBeNoLongerThan(100);
    }

    //image relation

    public function thumbnailImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->ofMany([
            'position' => 'MIN'
        ], function(Builder $query) {
            $query->where('is_saved', true);
        });
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    //for tour type

    public function translations(): HasMany
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function translate(): HasOne
    {
        return $this->hasOne(ProductTranslation::class)->ofMany([
            'id' => 'MAX'
        ], function(Builder $query) {
            $query->where('lang', '=', app()->getLocale());
        });
    }
    
    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class, 'pivot_product_location',  'product_id', 'location_id');
    }

    //for transport type
    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class, 'pivot_product_facility',  'product_id', 'facility_id');
    }

}

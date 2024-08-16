<?php

namespace App\Models;

use App\Models\Translation\ProductTranslation;
use App\Observers\AdminActionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

#[ObservedBy([AdminActionObserver::class])]
class Product extends Model
{
    use HasFactory, HasSlug;

    public const TRANSPORT_TYPE   = 'transport';

    public const TOUR_TYPE        = 'tour';

    public function scopeTransport(Builder $query, ?string $slug = null): void
    {
        if ($slug == null) {
            $query->where('type', '=', static::TRANSPORT_TYPE);
        } else {
            $query->where('type', '=', static::TRANSPORT_TYPE)->where('slug', '=', $slug);
        }
    }

    public function scopeTour(Builder $query, ?string $slug = null): void
    {
        if ($slug == null) {
            $query->where('type', '=', static::TOUR_TYPE);
        } else {
            $query->where('type', '=', static::TOUR_TYPE)->where('slug', '=', $slug);
        }
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function scopeTripPrice(Builder $query, float $distance): void
    {
        $result = round($distance);

        $query->select(
            '*',
            DB::raw('price + (price_per_km * ' . $result . ') as trip_price')
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

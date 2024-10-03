<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Tour extends Model
{
    use HasFactory, HasSlug, SoftDeletes;

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
                ->generateSlugsFrom('name')
                ->saveSlugsTo('slug');
    }

    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class, PivotTourLocation::class, 'tour_id', 'location_id');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(TourVariant::class, 'tour_id', 'id');
    }

    public function defaultVariant(): HasOne
    {
        return $this->hasOne(TourVariant::class, 'tour_id', 'id')->where('is_default', '=', 1);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(TourTranslation::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(TourImage::class);
    }

    public function thumbnailImage(): HasOne
    {
        return $this->hasOne(TourImage::class)->ofMany([
            'position' => 'MIN'
        ], function($query) {
            $query->where('position', '!=', null);
        });
    }

    public function scopeActive($query)
    {
        $query->where('is_active', '=', 1);
    }

    public function scopeSelectThumbnailVariantPrice($query)
    {
        if (app()->getLocale() == 'id') {
            $query->addSelect('*',DB::raw(
                "(select price from tour_variants where tour_id = tours.id and deleted_at is null order by price asc limit 1) as price"
            ));
        } else {
            $query->addSelect('*',DB::raw(
                "(select usd_price from tour_variants where tour_id = tours.id and deleted_at is null order by price asc limit 1) as price"
            ));
        }
        
        
    }

    public function scopeSelectThumbnailVariantMaxPerson($query)
    {
        $query->addSelect('*',DB::raw(
            "(select max_person from tour_variants where tour_id = tours.id and deleted_at is null order by max_person asc limit 1) as max_person"
        ));
    }

    public function scopeLocationId(Builder $query, $location_id)
    {
        $query->whereHas('locations', function(Builder $query) use($location_id) {
            $query->where('location_id', '=', $location_id);
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Tour extends Model
{
    use HasFactory, HasSlug;

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

    public function thumbnailVariant()
    {
        return $this->hasOne(TourVariant::class, 'tour_id', 'id')->ofMany('price', 'MIN');
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
}

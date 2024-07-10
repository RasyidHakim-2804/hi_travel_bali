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


    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
                ->generateSlugsFrom('name')
                ->saveSlugsTo('slug')
                ->startSlugSuffixFrom('2')
                ->slugsShouldBeNoLongerThan(100);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function transalate(): HasOne
    {
        return $this->hasOne(ProductTranslation::class);
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class, 'pivot_product_facility',  'product_id', 'facility_id');
    }

    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class, 'pivot_product_location',  'product_id', 'location_id');
    }
}

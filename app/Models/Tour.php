<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
                ->saveSlugsTo('slug')
                ->startSlugSuffixFrom('2')
                ->slugsShouldBeNoLongerThan(100);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(TourTranslation::class);
    }

    public function descriptionTranslations(): HasMany
    {
        return $this->hasMany(TourTranslation::class, 'tour_id', 'id')->where('field', '=', 'description');
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
            $query->where('is_saved', true);
        });
    }
}

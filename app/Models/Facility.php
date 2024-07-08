<?php

namespace App\Models;

use App\Models\Translation\FacilityTranslation;
use App\Service\Contracts\Model\CanUploadImage;
use App\Service\Traits\HasImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Facility extends Model implements CanUploadImage
{
    use HasFactory, HasImage, HasSlug;

    protected $table            = 'facilities';
    protected $imageUrlColumn   = 'icon_url';
    protected $format           = '.webp';
    protected $disk             = 'public';
    protected $dirPath          = 'upload/images/facility/';

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
                ->generateSlugsFrom('name')
                ->saveSlugsTo('slug')
                ->slugsShouldBeNoLongerThan(100)
                ->startSlugSuffixFrom(2);
    }

    //Accessor path
    public function iconUrl(): Attribute
    {
        return new Attribute(
            get: function ($value = null) {
                if ($value == null) {
                    return null;
                }
                return  config('app.url') . 'storage/' . $value;
            }
        );
    }

    public function translations(): HasMany
    {
        return $this->hasMany(FacilityTranslation::class);
    }

    public function translatate(): HasOne
    {
        return $this->hasOne(FacilityTranslation::class);
    }
}

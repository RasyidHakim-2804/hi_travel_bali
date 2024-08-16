<?php

namespace App\Models;

use App\Observers\AdminActionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

#[ObservedBy([AdminActionObserver::class])]
class Location extends Model
{
    use HasFactory, HasSlug;

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
                ->generateSlugsFrom('name')
                ->saveSlugsTo('slug')
                ->slugsShouldBeNoLongerThan(100)
                ->startSlugSuffixFrom(2);
    
    }
}

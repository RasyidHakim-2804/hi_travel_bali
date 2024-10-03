<?php

namespace App\Models;

use App\Traits\ImageStorageTrait;
use Exception;
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

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class, 'pivot_car_facility');
    }

    public function scopeActive($query): void
    {
        $query->where('is_active', true);
    }

    public function scopePassengerCount($query, int $passengerCount)
    {
        $query->where('max_person', '>=', $passengerCount);
    }

    public function scopeDistance($query, $distance)
    {
        if ($distance < 1) {
            throw new Exception('the distance cannot be smaller than 1');
        }

        if (app()->getLocale() == 'en') {
            $query->select(
                '*',
                DB::raw('usd_price * ' . $distance . ' as trip_price')
            );
        } else {
            $query->select(
                '*',
                DB::raw('price * ' . $distance . ' as trip_price')
            );
        }
        
    }
}

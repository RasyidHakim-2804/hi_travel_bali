<?php

namespace App\Models;

use App\Traits\ImageStorageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Car extends Model
{
    use HasFactory, ImageStorageTrait;

    protected $imageSetting = [
        'folder' => 'car',
        'field'  => [
            'image',
        ],
    ];

    public function driver(): BelongsToMany
    {
        return $this->belongsToMany(Driver::class, PivotDriverCar::class, 'car_id', 'driver_id');
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class, 'pivot_car_facility');
    }
}

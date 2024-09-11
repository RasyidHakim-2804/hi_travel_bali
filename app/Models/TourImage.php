<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ImageStorageTrait;
use Illuminate\Database\Eloquent\Builder;

class TourImage extends Model
{
    use HasFactory, ImageStorageTrait;

    protected $imageSetting = [
        'folder' => 'tour',
        'field'  => [
            'image',
        ],
    ];
    
    public function product(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function scopeSaved(Builder $query,)
    {
        $query->where('position', '!=', null);
    }
}

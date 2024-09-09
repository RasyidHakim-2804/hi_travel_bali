<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
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
        return $this->belongsTo(Product::class);
    }

    public function scopeSaved(Builder $query, bool $saved = true)
    {
        $query->where('is_saved', '=', $saved);
    }
}

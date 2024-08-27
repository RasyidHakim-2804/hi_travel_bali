<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Location extends Model
{
    use HasFactory;

    public function tours(): BelongsToMany
    {
        return $this->belongsToMany(Tour::class, 'pivot_tour_location', 'location_id', 'tour_id' );
    }
}

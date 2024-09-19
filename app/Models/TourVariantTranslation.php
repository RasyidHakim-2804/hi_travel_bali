<?php

namespace App\Models;

use App\Traits\IsTranslationTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourVariantTranslation extends Model
{
    use HasFactory, IsTranslationTrait;

    public function tourVariant(): BelongsTo
    {
        return $this->belongsTo(TourVariant::class, 'tour_variant_id', 'id');
    }
}

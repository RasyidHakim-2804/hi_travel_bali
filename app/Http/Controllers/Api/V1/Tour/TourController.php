<?php

namespace App\Http\Controllers\Api\V1\Tour;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Product\Tour\UserListRequest;
use App\Http\Resources\Api\V1\Product\ProductResource;
use App\Http\Resources\Tour\TourResource;
use App\Models\Product;
use App\Models\Tour;
use App\Traits\JsonResponsesTrait;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class TourController extends Controller
{
    use JsonResponsesTrait;

    public function list()
    {
        $tours = QueryBuilder::for(Tour::class)
                ->defaultSort('max_person')
                ->allowedSorts([
                    AllowedSort::field('price', 'idr_price'),
                    AllowedSort::field('recomended', 'max_person'),
                ])
                ->with([
                    'thumbnailImage',
                    'locations',
                    'thumbnailVariant',
                ])
                ->active()
                ->paginate(25);

        return TourResource::collection($tours);
    }

    public function show(Tour $tour)
    {
        $tour = $tour->load([
                    'locations',
                    'variants',
                    'translations' => fn($query) => $query->language(),
                    'images' => fn($query) => $query->saved()
                ]);

        return $this->successGet(
            new TourResource($tour)
        );
    }

    public function popular()
    {
        return TourResource::collection(
            Tour::with([
                'thumbnailImage',
                'locations',
                'thumbnailVariant',
            ])
            ->active()
            ->orderBy('id', 'DESC')
            ->limit(4)
            ->get()
        );
    }
}

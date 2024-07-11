<?php

namespace App\Http\Controllers\Api\V1\Product\Tour;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Product\Tour\UserListRequest;
use App\Http\Resources\Api\V1\Product\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class TourController extends Controller
{
    public function list(UserListRequest $request)
    {
        $tours = QueryBuilder::for(Product::class)
                ->defaultSort('max_person')
                ->allowedSorts([
                    AllowedSort::field('price', 'idr_price'),
                    AllowedSort::field('recomended', 'max_person'),
                ])
                ->with([
                    'thumbnailImage',
                    'locations'
                ])
                ->tour()
                ->active()
                ->where('max_person', '>=', $request->tourist_numbers)
                ->paginate(25);

        return ProductResource::collection($tours);
    }

    public function show($slug)
    {
        $tour = Product::with([
                    'locations',
                    'translate',
                    'images' => fn($query) => $query->where('is_saved',true)
                ])
                ->where('slug', $slug)
                ->tour()
                ->active()
                ->firstOrFail();

        return new ProductResource($tour);
    }

    public function popular()
    {
        $tours = Product::with([
                    'thumbnailImage',
                    'locations'
                ])
                ->tour()
                ->active()
                ->orderBy('popular', 'DESC')
                ->paginate(4);
        
        return ProductResource::collection($tours);
    }
}

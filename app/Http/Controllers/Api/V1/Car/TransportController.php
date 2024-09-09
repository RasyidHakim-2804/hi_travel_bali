<?php

namespace App\Http\Controllers\Api\V1\Car;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Product\Transport\UserListRequest;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class TransportController extends Controller
{
    public function list(UserListRequest $request)
    {
        $cars = QueryBuilder::for(Product::class)
                ->defaultSort('max_person')
                ->allowedSorts([
                    AllowedSort::field('price', 'trip_price'),
                    AllowedSort::field('recomended', 'max_person'),
                ])
                ->with([
                    'thumbnailImage',
                    'facilities'
                ])
                ->transport()
                ->active()
                ->tripPrice($request->distance)
                ->where('max_person', '>=', $request->passenger)
                ->paginate(25);
        
        return ProductResource::collection($cars);
    }
}

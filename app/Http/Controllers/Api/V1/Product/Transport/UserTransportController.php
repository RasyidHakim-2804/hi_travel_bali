<?php

namespace App\Http\Controllers\Api\V1\Product\Transport;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Product\Transport\UserListRequest;
use App\Http\Resources\Api\V1\Product\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class UserTransportController extends Controller
{
    public function list(UserListRequest $request)
    {
        $cars = QueryBuilder::for(Product::class)
                ->defaultSort('max_person')
                ->allowedSorts([
                    AllowedSort::field('price', 'idr_price'),
                    AllowedSort::field('recomended', 'max_person'),
                ])
                ->with([
                    'thumbnailImage',
                    'facilities'
                ])
                ->tripPrice($request->distance)
                ->transport()
                ->where('max_person', '>=', $request->passenger)
                ->paginate(25);
        
        return ProductResource::collection($cars);
    }
}

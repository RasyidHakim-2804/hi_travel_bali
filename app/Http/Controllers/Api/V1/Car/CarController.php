<?php

namespace App\Http\Controllers\Api\V1\Car;

use App\Http\Controllers\Controller;
use App\Http\Resources\Car\CarResource;
use App\Models\Car;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class CarController extends Controller
{
    public function list()
    {
        $cars = QueryBuilder::for(Car::class)
                ->defaultSort('max_person')
                ->allowedSorts([
                    AllowedSort::field('price'),
                    AllowedSort::field('recomended', 'max_person'),
                ])
                ->allowedFilters([
                    AllowedFilter::scope('passenger_count', 'passengerCount'),
                    AllowedFilter::scope('distance', 'distance')
                ])
                ->with([
                    'facilities.translations' => fn($query) => $query->language()
                ])
                ->active()
                ->paginate(25);
        
        return CarResource::collection($cars);
    }
}

<?php

namespace App\Http\Controllers\Api\V1\Location;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Location\LocationRequest;
use App\Http\Resources\Api\V1\Common\SuccessResource;
use App\Http\Resources\Api\V1\Location\LocationResource;
use App\Models\Location;
use Illuminate\Http\Request;

class AdminLocationController extends Controller
{
    public function index()
    {
        $locations = Location::paginate(25);

        return LocationResource::collection($locations);
    }

    public function show(Location $location)
    {
        return new LocationResource($location);   
    }

    public function store(LocationRequest $request)
    {
        $location       = new Location();
        $location->name = $request->name;
        $location->save();

        return new SuccessResource(201);
    }

    public function update(LocationRequest $request, Location $location)
    {
        $location->name = $request->name;
        $location->save();

        return new SuccessResource();
    }

    public function destroy(Location $location)
    {
        $location->delete();

        return new SuccessResource();
    }
}

<?php

namespace App\Http\Controllers\Api\V1\Location;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Location\LocationRequest;
use App\Http\Resources\Common\SuccessResource;
use App\Http\Resources\Location\LocationResource;
use App\Models\Location;
use App\Traits\JsonResponsesTrait;

class AdminLocationController extends Controller
{
    use JsonResponsesTrait;

    public function index()
    {
        $locations = Location::paginate(25);

        return LocationResource::collection($locations);
    }

    public function show(Location $location)
    {
        return $this->successGet(
            new LocationResource($location)
        );   
    }

    public function store(LocationRequest $request)
    {
        $location       = new Location();
        $location->name = $request->name;
        $location->save();

        return $this->successStore();
    }

    public function update(LocationRequest $request, Location $location)
    {
        $location->name = $request->name;
        $location->save();

        return $this->successResponse();
    }

    public function destroy(Location $location)
    {
        $location->tours()->detach();
        $location->delete();

        return $this->successResponse();
    }
}

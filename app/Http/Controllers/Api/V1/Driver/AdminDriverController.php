<?php

namespace App\Http\Controllers\Api\V1\Driver;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Driver\DriverRequest;
use App\Http\Resources\Common\SuccessResource;
use App\Http\Resources\Driver\DriverResource;
use App\Models\Driver;
use App\Traits\JsonResponsesTrait;

class AdminDriverController extends Controller
{
    use JsonResponsesTrait;

    public function index()
    {
        return DriverResource::collection(Driver::paginate(25));
    }

    public function all()
    {
        return DriverResource::collection(Driver::all(['id', 'name']));
    }

    public function show(Driver $driver)
    {
        return $this->successGet(
            new DriverResource($driver)
        );
    }

    public function store(DriverRequest $request)
    {
        $driver = new Driver();
        $driver->name   = $request->name;
        $driver->phone  = $request->phone;
        $driver->email  = $request->email;
        $driver->save();

        return $this->successStore();
    }

    public function update(DriverRequest $request, Driver $driver)
    {
        $driver->name   = $request->name;
        $driver->phone  = $request->phone;
        $driver->email  = $request->email;
        $driver->save();

        return $this->successResponse();
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();

        return $this->successResponse();
    }
}

<?php

namespace App\Http\Controllers\Api\V1\Car;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Car\CarRequest;
use App\Http\Resources\Common\SuccessResource;
use App\Http\Resources\Car\CarResource;
use App\Models\Car;
use App\Services\Images\ImageService;
use App\Traits\JsonResponsesTrait;
use Illuminate\Support\Facades\DB;

class AdminCarController extends Controller
{
    use JsonResponsesTrait;

    public function index()
    {
        $cars = Car::paginate(25);

        return CarResource::collection($cars);
    }

    public function show(Car $car)
    {
        $car = $car->load([
            'facilities'
        ]);

        return $this->successGet(
            new CarResource($car)
        );
    }

    public function store(CarRequest $request)
    {
        try {
            DB::beginTransaction();

            $car                = new Car();
            $car->name          = $request->name;
            $car->price         = $request->price;
            $car->is_active     = $request->is_active;
            $car->image         = $this->setImage($car, $request->image);
            $car->save();

            $car->facilities()->attach($request->facilities);

            DB::commit();

            return $this->successStore();

        } catch (\Exception $e) {

            DB::rollBack();

            return $this->errorServer($e->getMessage());
        }
    }

    public function update(CarRequest $request, Car $car)
    {
        try {
            $car->name          = $request->name;
            $car->price         = $request->price;
            $car->is_active     = $request->is_active;

            if ($request->image != null) {
                $car->image         = $this->setImage($car, $request->image);
            }

            $car->save();

            $car->facilities()->sync($request->facilities);

            DB::commit();

            return $this->successResponse();

        } catch (\Exception $e) {

            DB::rollBack();
            
            return $this->errorServer($e->getMessage());
        }
    }

    public function destroy(Car $car)
    {
        try {
            DB::beginTransaction();

            $car->facilities()->detach();
            $car->driver()->detach();
            $car->delete();

            DB::commit();

            return $this->successResponse();

        } catch (\Exception $e) {
            
            DB::rollBack();

            return $this->errorServer($e->getMessage());
        }
    }

    private function setImage(Car $car, $requestImage)
    {
        $image = new ImageService($car->imagePath());
        
        if ($car->image) {
            $image->delete($car->image);
        }

        $image->save($requestImage);

        return $image->getImageName();
    }

}

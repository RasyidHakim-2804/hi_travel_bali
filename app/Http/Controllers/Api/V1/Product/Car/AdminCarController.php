<?php

namespace App\Http\Controllers\Api\V1\Product\Car;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Product\Car\CreateCarRequest;
use App\Http\Requests\Api\V1\Product\Car\UpdateCarRequest;
use App\Http\Resources\Api\V1\Common\SuccessResource;
use App\Http\Resources\Api\V1\Product\ProductResource;
use App\Models\Product;
use App\Service\Services\ImageServices\ImageService;
use Illuminate\Support\Facades\DB;

class AdminCarController extends Controller
{
    public function index()
    {
        $cars = Product::where('type', Product::CAR_TYPE)->paginate(25);

        return ProductResource::collection($cars);
    }

    public function show(Product $car)
    {
        return new ProductResource($car->load('facilities'));
    }

    public function store(CreateCarRequest $request)
    {
        try {
            DB::beginTransaction();

            $car                = new Product();
            $car->name          = $request->name;
            $car->type          = Product::CAR_TYPE;
            $car->idr_price     = $request->idr_price;
            $car->max_person    = $request->max_person;

            $car->save();

            $car->facilities()->attach($request->facilities);

            DB::commit();

            return new SuccessResource(201);

        } catch (\Throwable $th) {

            DB::rollBack();
            
            throw $th;
        }
    }

    public function update(UpdateCarRequest $request, Product $car)
    {
        try {
            DB::beginTransaction();

            $car->name          = $request->name;
            $car->idr_price     = $request->idr_price;
            $car->max_person    = $request->max_person;
            $car->is_active     = $request->is_active;

            $car->save();

            $car->facilities()->sync($request->facilities);

            DB::commit();

            return new SuccessResource(201);

        } catch (\Throwable $th) {

            DB::rollBack();
            
            throw $th;
        }
    }

    public function destroy(Product $car)
    {
        try {
            DB::beginTransaction();

            $imageServices  = [];

            foreach ($car->images() as $image) {
                $imageService = new ImageService($image);
                $imageService->delete();
                $imageServices[] = $imageService;
                $image->delete();
            }

            $car->facilities()->detach();
            $car->delete();

            DB::commit();

            return new SuccessResource();

        } catch (\Throwable $th) {
            
            DB::rollBack();

            if ( count($imageServices) != 0 ) {
                foreach ($imageServices as $imageService) {
                    $imageService->rollback();
                }
            }
            
            throw $th;
        }
    }

}

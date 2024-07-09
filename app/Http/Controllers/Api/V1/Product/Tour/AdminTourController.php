<?php

namespace App\Http\Controllers\Api\V1\Product\Tour;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Product\Tour\CreateTourRequest;
use App\Http\Requests\Api\V1\Product\Tour\TourRequest;
use App\Http\Requests\Api\V1\Product\Tour\UpdateTourRequest;
use App\Http\Resources\Api\V1\Common\SuccessResource;
use App\Http\Resources\Api\V1\Product\ProductResource;
use App\Models\Product;
use App\Models\Translation\ProductTranslation;
use App\Service\Services\ImageServices\ImageService;
use Illuminate\Support\Facades\DB;

class AdminTourController extends Controller
{
    public function index()
    {
        $tours = Product::where('type', Product::TOUR_TYPE)->paginate(25);

        return ProductResource::collection($tours);
    }

    public function show(Product $tour)
    {
        return new ProductResource($tour->load([
            'translations',
            'locations',
        ]));
    }

    public function store(CreateTourRequest $request)
    {
        try {
            DB::beginTransaction();

            $tour               = new Product();
            $request            = $request->validated();

            $tour->name         = $request['name'];
            $tour->type         = Product::TOUR_TYPE;
            $tour->max_person   = $request['max_person'];
            $tour->idr_price    = $request['idr_price'];
            $tour->save();

            $tour->locations()->attach($request['locations']);

            foreach ( ['id', 'en'] as $lang) {
                $translation                = new ProductTranslation();
                $translation->product_id    = $tour->id;
                $translation->lang          = $lang;
                $translation->description   = $request[$lang . '_description'];
                $translation->save();
            }

            DB::commit();

            return new SuccessResource(201);

        } catch (\Throwable $th) {
            
            DB::rollBack();

            throw $th;
        }
    }

    public function update(UpdateTourRequest $request, Product $tour)
    {
        try {
            DB::beginTransaction();

            $request            = $request->validated();

            $tour->name         = $request['name'];
            $tour->max_person   = $request['max_person'];
            $tour->idr_price    = $request['idr_price'];
            $tour->is_active    = $request['is_active'];
            $tour->save();

            $tour->locations()->sync($request['locations']);

            foreach ( ['id', 'en'] as $lang) {
                $translation                = ProductTranslation::where('lang', $lang)
                                                ->where('product_id', $tour->id)
                                                ->first();
                                                
                $translation->description   = $request[$lang . '_description'];
                $translation->save();
            }

            DB::commit();

            return new SuccessResource(200);

        } catch (\Throwable $th) {
            
            DB::rollBack();

            throw $th;
        }
    }

    public function destroy(Product $tour)
    {
        try {
            DB::beginTransaction();

            $imageServices  = [];

            foreach ($tour->images() as $image) {
                $imageService = new ImageService($image);
                $imageService->delete();
                $imageServices[] = $imageService;
                $image->delete();
            }

            $tour->locations()->detach();
            $tour->translations()->delete();
            $tour->delete();

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

<?php

namespace App\Http\Controllers\Api\V1\Product\Tour;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Product\Tour\CreateTourRequest;
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
        $tours = Product::tour()->paginate(25);

        return ProductResource::collection($tours);
    }

    public function show($slug)
    {
        $tour = Product::with(['locations','translations'])
                ->tour($slug)
                ->firstOrFail();

        return new ProductResource($tour);
    }

    public function store(CreateTourRequest $request)
    {
        try {
            DB::beginTransaction();

            $tour               = new Product();
            $request            = $request->validated();

            $tour->name         = $request['name'];
            $tour->type         = $tour::TOUR_TYPE;
            $tour->max_person   = $request['max_person'];
            $tour->idr_price    = $request['idr_price'];
            $tour->save();

            $tour->locations()->attach($request['locations']);

            $this->setTranslation($request, $tour->id);

            DB::commit();

            return new SuccessResource(201);

        } catch (\Throwable $th) {
            
            DB::rollBack();

            throw $th;
        }
    }

    public function update(UpdateTourRequest $request, $slug)
    {
        try {
            DB::beginTransaction();

            $tour = Product::tour($slug)->firstOrFail();

            $request            = $request->validated();

            $tour->name         = $request['name'];
            $tour->max_person   = $request['max_person'];
            $tour->idr_price    = $request['idr_price'];
            $tour->is_active    = $request['is_active'];
            $tour->save();

            $tour->locations()->sync($request['locations']);

            $this->setTranslation($request, $tour->id);

            DB::commit();

            return new SuccessResource(200);

        } catch (\Throwable $th) {
            
            DB::rollBack();

            throw $th;
        }
    }

    public function destroy($slug)
    {
        try {
            DB::beginTransaction();

            $tour   = Product::tour($slug)->firstOrFail();
            $images = $tour->images;
            
            if ($images != null) {
                $imageServices  = ImageService::collection($tour->images);
                $imageServices->delete();
                $tour->images()->delete();
            }

            $tour->locations()->detach();
            $tour->translations()->delete();
            $tour->delete();

            DB::commit();

            return new SuccessResource();

        } catch (\Throwable $th) {
            
            DB::rollBack();
            
            $imageServices->rollback();
            
            throw $th;
        }
    }

    private function setTranslation( array $request, $tour_id)
    {
        foreach ( ['id', 'en'] as $lang) {
            $translation                = ProductTranslation::where('lang', $lang)
                                            ->where('product_id', $tour_id)
                                            ->firstOrNew();
                                            
            $translation->description   = $request[$lang . '_description'];
            $translation->product_id    = $tour_id;
            $translation->lang          = $lang;
            $translation->save();
        }
    }
}

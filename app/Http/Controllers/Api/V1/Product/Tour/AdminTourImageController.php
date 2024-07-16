<?php

namespace App\Http\Controllers\Api\V1\Product\Tour;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Product\Tour\Image\DeleteImagerequest;
use App\Http\Requests\Api\V1\Product\Tour\Image\SaveImageRequest;
use App\Http\Requests\Api\V1\Product\Tour\Image\StoreImageRequest;
use App\Http\Resources\Api\V1\Product\ProductImageResource;
use App\Http\Resources\Api\V1\Common\SuccessResource;
use App\Models\Product;
use App\Models\Image\ProductImage;
use App\Service\Services\ImageServices\ImageService;
use Illuminate\Support\Facades\DB;

class AdminTourImageController extends Controller
{
    public function index($slug)
    {
        $productTour   = Product::tour($slug)->firstOrFail();

        $images = $productTour->images()->saved()->get();

        return ProductImageResource::collection($images);
    }

    public function store( $slug,StoreImageRequest $request)
    {
        try {
            DB::beginTransaction();

            $productTour   = Product::tour($slug)->firstOrFail();
            $image         = new ProductImage();
            $imageService  = new ImageService($image);
            $result        = $imageService->upload($request->images, 'tour');            

            $image->product_id  = $productTour->id;
            $image->path        = $result->path;
            $image->is_saved    = false;
            $image->save();

            DB::commit();

            return new ProductImageResource($image);

        } catch (\Throwable $th) {
            
            DB::rollBack();

            if (isset($imageService)) {
                $imageService->rollback();
            }

            throw $th;
        }
    }

    public function delete(DeleteImagerequest $request, $slug)
    {
        try {
            DB::beginTransaction();

            $productTour = Product::tour($slug)->firstOrFail();
            $image       = $productTour->images()->where('id', $request->image_id)->firstOrFail();

            $imageService = new ImageService($image);
            $imageService->delete();
            $image->delete();

            DB::commit();

            return new SuccessResource();
            
        } catch (\Throwable $th) {
            
            DB::rollBack();
            
            if (isset($imageService)) {
                $imageService->rollback();
            }

            throw $th;
        }
    }

    public function saveImages(SaveImageRequest $request, $slug)
    {
        try {
            DB::beginTransaction();

            $productTour  = Product::tour($slug)->firstOrFail();
            $imagesId     = $request->validated('images_id');

            for ($i = 0; $i < count($imagesId); $i++) { 
                ProductImage::where('product_id', $productTour->id)
                            ->where('id', $imagesId[$i])
                            ->update([
                                'is_saved'  => true,
                                'position'  => $i + 1
                            ]);
            }
            
            $unsavedImages = $productTour->images()->saved(false)->get();
            
            if (!$unsavedImages->isEmpty()) {
                $imageServices = ImageService::collection($unsavedImages);
                $imageServices->delete();
                $productTour->images()->saved(false)->delete();
            }
        
            DB::commit();

            return new SuccessResource();

        } catch (\Throwable $th) {
            DB::rollBack();

            if (isset($imageServices)) {
                $imageServices->rollback();
            }
            
            throw $th;
        }
    }
}

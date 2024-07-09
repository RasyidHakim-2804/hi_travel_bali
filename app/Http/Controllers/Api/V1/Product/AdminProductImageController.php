<?php

namespace App\Http\Controllers\Api\V1\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Product\UploadProductImageRequest;
use App\Models\Image\ProductImage;
use App\Models\Product;
use App\Service\Services\ImageServices\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminProductImageController extends Controller
{
    public function index(Product $product)
    {
        $images = $product->images()->where('is_saved', '=', 1)->get();

        return ProductImageResource::collection($images);
    }

    public function store(Product $product,UploadProductImageRequest $request)
    {
        try {
            DB::beginTransaction();

            $image              = new ProductImage();
            $lastPosition       = ProductImage::where('product_id', $product->id)
                                    ->orderByDesc('id')
                                    ->value('position');

            $imageService       = new ImageService($image);
            $result             = $imageService->upload($request->images);            

            $image->product_id  = $product->id;
            $image->name        = $result->name;
            $image->path        = $result->path;
            $image->position    = $lastPosition + 1;
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

    public function delete( $request,Product $product)
    {
        try {
            DB::beginTransaction();

            $image = ProductImage::where('id', $request->image_id)
                        ->where('product_id', $product->id)
                        ->first();

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

    public function saveImages(SaveImageRequest $request, Product $product)
    {
        DB::transaction(function() use ($request, $product)
        {
            $imagesId     = $request->validated()['images_id'];
            $position     = 1;
            $undeletedIds = [];
            sort($imagesId);

            if ( count($imagesId) != 0 ) {
                foreach ( $imagesId as $key => $id ) {
                    $image           = ProductImage::findOrFail($id);
                    $image->is_saved = true;
                    $image->position = $position;
                    $image->save();
        
                    $position++;
                    $undeletedIds[] = $id;
                }   
            }

            $this->deleteById($product->id, $undeletedIds);
        });
        
        return new SuccessResource();
    }

    ////////////////////
    // private methods//
    ////////////////////

    private function deleteById($productId, array $undeletedIds)
    {
        $imageServices = [];

        try {
            DB::beginTransaction();

            $images = ProductImage::query()
                        ->where('product_id', $productId)
                        ->whereNotIn('id', $undeletedIds)
                        ->get();

            foreach ($images as $image ) {
                $imageService = new ImageService($image);
                $imageService->delete();
                $imageServices[] = $imageService;
                $image->delete();
            }

            DB::commit();

        } catch (\Throwable $th) {

            DB::rollBack();

            if (!empty($imageServices)) {
                foreach ($$imageServices as $imageService) {
                    $imageService->rollback();
                }
            }

            throw $th;
        }
    }
}

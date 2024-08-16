<?php

namespace App\Http\Controllers\Api\V1\Product\Tour;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Product\Tour\Image\SaveTourImageRequest;
use App\Http\Requests\Api\Product\Tour\Image\StoreTourImageRequest;
use App\Http\Resources\Common\SuccessResource;
use App\Http\Resources\Product\ProductImageResource;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\Admin\AdminPermissionService;
use App\Services\Images\ImageService;
use Illuminate\Support\Facades\DB;

class AdminTourImageController extends Controller
{
    protected $permissionService;

    public function __construct()
    {
        $this->permissionService = new AdminPermissionService('activity_image');
    }

    public function index(Product $tour)
    {
        $images = $tour->images()->saved()->get();

        return ProductImageResource::collection($images);
    }

    public function store(Product $tour, StoreTourImageRequest $request)
    {
        try {
            DB::beginTransaction();

            $tourImage = new ProductImage();
            $imageService  = new ImageService($tourImage->imagePath(), $request->image);
            $imageService->save();          

            $tourImage->activity_id  = $tour->id;
            $tourImage->image        = $imageService->getImageName();
            $tourImage->position     = null;
            $tourImage->save();

            DB::commit();

            return new SuccessResource(
                data: new ProductImageResource($tourImage)
            );

        } catch (\Throwable $th) {
            
            DB::rollBack();

            throw $th;
        }
    }

    public function save(SaveTourImageRequest $request, Product $tour)
    {
        try {
            DB::beginTransaction();

            $imagesId       = $request->validated('images_id');
            $savedImages    = $tour->images()->whereIn('id', $imagesId)->get();
            $unsavedImages  = $tour->images()->whereNotIn('id', $imagesId)->get();                                                                   

            $tour->images()->whereNotIn('id', $imagesId)->delete(); 
            
            foreach ($imagesId as $position => $id) {
                $image           = $savedImages->where('id', '=', $id)->firstOrFail();
                $image->position = $position + 1;
                $image->save();
            }

            foreach ($unsavedImages as $unsavedImage) {
                $imageService = new ImageService($unsavedImage->imagePath());
                $imageService->delete($unsavedImage->image);
            }

            DB::commit();            

            return new SuccessResource();

        } catch (\Throwable $th) {
            
            DB::rollBack();
            
            throw $th;
        }
            
    }
}

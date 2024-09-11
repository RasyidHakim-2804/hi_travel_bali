<?php

namespace App\Http\Controllers\Api\V1\Tour;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Tour\SaveTourImageRequest;
use App\Http\Requests\Api\Tour\StoreTourImageRequest;
use App\Http\Resources\Common\SuccessResource;
use App\Http\Resources\Tour\TourImageResource;
use App\Models\Tour;
use App\Models\TourImage;
use App\Services\Admin\AdminPermissionService;
use App\Services\Images\ImageService;
use App\Traits\JsonResponsesTrait;
use Illuminate\Support\Facades\DB;

class AdminTourImageController extends Controller
{
    use JsonResponsesTrait;

    protected $permissionService;

    public function __construct()
    {
        $this->permissionService = new AdminPermissionService('activity_image');
    }

    public function index(Tour $tour)
    {
        return TourImageResource::collection(
            $tour->images()->saved()->get()
        );
    }

    public function store(Tour $tour, StoreTourImageRequest $request)
    {
        try {
            DB::beginTransaction();

            $tourImage      = new TourImage();
            $imageService   = new ImageService($tourImage->imagePath(), $request->image);
            $imageService->save();          

            $tourImage->tour_id     = $tour->id;
            $tourImage->image       = $imageService->getImageName();
            $tourImage->position    = null;
            $tourImage->save();

            DB::commit();

            return $this->successStoreWithData(
                new TourImageResource($tourImage)
            );

        } catch (\Throwable $th) {
            
            DB::rollBack();

            throw $th;
        }
    }

    public function save(SaveTourImageRequest $request, Tour $tour)
    {
        try {
            DB::beginTransaction();

            $imagesId       = $request->validated('images_id');
            $unsavedImages  = $tour->images()->whereNotIn('id', $imagesId)->get();                                                               
            $savedImages    = $tour->images()->whereIn('id', $imagesId)->get();
            
            $position = 1;

            foreach ($savedImages as $savedImage) {
                $savedImage->position = $position;
                $savedImage->save();
                $position++;
            }

            foreach ($unsavedImages as $unsavedImage) {
                $imageService = new ImageService($unsavedImage->imagePath());
                $imageService->delete($unsavedImage->image);
                $unsavedImage->delete();
            }

            DB::commit();            

            return $this->successResponse();

        } catch (\Throwable $th) {
            
            DB::rollBack();
            
            throw $th;
        }
            
    }
}

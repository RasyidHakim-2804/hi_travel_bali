<?php

namespace App\Http\Controllers\Api\V1\Facility;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Facility\CreateFacilityRequest;
use App\Http\Requests\Api\Facility\UpdateFacilityRequest;
use App\Http\Resources\Common\SuccessResource;
use App\Http\Resources\Facility\FacilityResource;
use App\Models\Facility;
use App\Models\Translation\FacilityTranslation;
use App\Service\Services\ImageServices\ImageService;
use Illuminate\Support\Facades\DB;

class AdminFacilityController extends Controller
{
    public function index()
    {
        $locations = Facility::paginate(25);

        return FacilityResource::collection($locations);
    }

    public function show(Facility $facility)
    {
        return new FacilityResource($facility->load('translations'));   
    }

    public function store(CreateFacilityRequest $request)
    {
        try {
            DB::beginTransaction();

            $facility       = new Facility();
            $imageService   = new ImageService($facility);
            
            $facility->icon_url = $imageService->upload($request->icon)->path;
            $facility->slug     = $request->en_name;
            $facility->save();

            $request = $request->validated();

            foreach (['id', 'en'] as $lang) {
                $translate              = new FacilityTranslation();
                $translate->lang        = $lang;
                $translate->name        = $request[$lang . '_name'];
                $translate->facility_id = $facility->id;
                $translate->save();
            }
    
            DB::commit();
            
            return new SuccessResource(201);

        } catch (\Throwable $th) {
            
            DB::rollBack();

            if (isset($imageService)) {
                $imageService->rollback();
            }

            throw $th;
        }
    }

    public function update(UpdateFacilityRequest $request, Facility $facility)
    {
        try {
            DB::beginTransaction();

            if ($request->has('icon')) {
                $imageService       = new ImageService($facility);
                $facility->icon_url = $imageService->upload($request->icon)->path; 
            }

            $facility->slug     = $request->en_name;
            $facility->save();

            $request    = $request->validated();

            foreach (['id', 'en'] as $lang) {
                $translate              = FacilityTranslation::where('facility_id', $facility->id)
                                            ->where('lang', $lang)
                                            ->first();
                                            
                $translate->name        = $request[$lang . '_name'];
                $translate->save();
            }
    
            DB::commit();
            
            return new SuccessResource(201);

        } catch (\Throwable $th) {
            
            DB::rollBack();

            if (isset($imageService)) {
                $imageService->rollback();
            }

            throw $th;
        }
    }

    public function destroy(Facility $facility)
    {
        try {
            DB::beginTransaction();
            
            $imageService = new ImageService($facility);
            $imageService->delete();
            $facility->translations()->delete();
            $facility->delete();

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
}

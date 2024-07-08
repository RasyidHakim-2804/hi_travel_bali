<?php

namespace App\Http\Controllers\Api\V1\Facility;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Facility\CreateFacilityRequest;
use App\Http\Requests\Api\V1\Facility\UpdateFacilityRequest;
use App\Http\Resources\Api\V1\Common\SuccessResource;
use App\Http\Resources\Api\V1\Facility\FacilityResource;
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
            
            $facility->name     = $request->name;
            $facility->icon_url = $imageService->upload($request->icon)->path; 
            $facility->save();

            $id_translate               = new FacilityTranslation();
            $id_translate->lang         = 'id';
            $id_translate->facility_id  = $facility->id;
            $id_translate->description  = $request->id_description;
            $id_translate->save();

            $en_translate               = new FacilityTranslation();
            $en_translate->lang         = 'en';
            $en_translate->facility_id  = $facility->id;
            $en_translate->description  = $request->en_description;
            $en_translate->save();
    
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
                $imageService   = new ImageService($facility);
                $facility->icon_url = $imageService->upload($request->icon)->path; 
            }
            
            $facility->name     = $request->name;
            $facility->save();

            $id_translate               = FacilityTranslation::where('facility_id', $facility->id)
                                            ->where('lang', 'id')
                                            ->first();

            $id_translate->description  = $request->id_description;
            $id_translate->save();

            $en_translate               = FacilityTranslation::where('facility_id', $facility->id)
                                            ->where('lang', 'en')
                                            ->first();

            $en_translate->description  = $request->en_description;
            $en_translate->save();
    
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

<?php

namespace App\Http\Controllers\Api\V1\Facility;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Facility\FacilityRequest;
use App\Http\Resources\Facility\DetailFacilityResource;
use App\Http\Resources\Facility\ListFacilityResource;
use App\Models\Facility;
use App\Services\Images\ImageService;
use App\Traits\JsonResponsesTrait;
use Illuminate\Support\Facades\DB;

class AdminFacilityController extends Controller
{
    use JsonResponsesTrait;

    protected $permissionService;

    public function __construct()
    {
        // $this->permissionService = (new AdminPermissionService('article'));
    }

    public function index()
    {
        $itineraries = Facility::with([
            'translations' => fn($query) => $query->language('en')
        ])->paginate(25);

        return ListFacilityResource::collection($itineraries);
    }

    public function all()
    {
        $itineraries = Facility::with([
            'translations' => fn($query) => $query->language('en')
        ])->get();

        return ListFacilityResource::collection($itineraries);
    }

    public function show(Facility $facility)
    {
        $facility = $facility->load('translations');
        
        return $this->successGet(
            new DetailFacilityResource($facility)
        );
    }

    public function store(FacilityRequest $request)
    {
        DB::transaction(function() use ($request) {

            $facility        = new Facility();
            $facility->image = $this->setImage($facility, $request->image);
            $facility->save();
            
            foreach ($request->validated('description') as $lang => $description) {
                $this->setTranslations($facility, $lang, 'description', $description);
            }
        });

        return $this->successStore();
    }

    public function update(Facility $facility, FacilityRequest $request)
    {
        DB::transaction(function() use ($request, $facility) {
            
            if ($request->image != null) {
                $facility->image = $this->setImage($facility, $request->image);
            }

            $facility->save();
            
            foreach ($request->validated('description') as $lang => $description) {
                $this->setTranslations($facility, $lang, 'description', $description);
            }
        });

        return $this->successResponse();
    }

    public function destroy(Facility $facility)
    {
        DB::beginTransaction();

        try {
        
            $facility->cars()->detach();
            $facility->delete();

            DB::commit();

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // Private function

    protected function setTranslations(Facility $facility, $lang, $field, $translation)
    {
        $facilityTranslation = $facility->translations()->where('language',$lang)->where('field', $field)->firstOrNew();

        $facilityTranslation->facility_id   = $facility->id;
        $facilityTranslation->language     = $lang;
        $facilityTranslation->field        = $field;
        $facilityTranslation->translation  = $translation;
        $facilityTranslation->save();
    }

    protected function setImage(Facility $facility, $requestImage)
    {
        $image = new ImageService($facility->imagePath());
        
        if ($facility->image) {
            $image->delete($facility->image);
        }

        $image->save($requestImage);

        return $image->getImageName();
    }
}

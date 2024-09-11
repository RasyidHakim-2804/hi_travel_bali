<?php

namespace App\Http\Controllers\Api\V1\Tour;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Tour\TourRequest;
use App\Http\Resources\Tour\AdminTourResource;
use App\Models\Tour;
use App\Services\Images\ImageService;
use App\Traits\JsonResponsesTrait;
use Illuminate\Support\Facades\DB;

class AdminTourController extends Controller
{
    use JsonResponsesTrait;

    public function index()
    {
        return AdminTourResource::collection(
            Tour::with('variants')->paginate(25)
        );
    }

    public function show(Tour $tour)
    {
        return $this->successGet(
            new AdminTourResource(
                $tour->load([
                    'locations',
                    'translations',
                    'variants'
                ])
            )
        );
    }

    public function store(TourRequest $request)
    {
        try {
            DB::beginTransaction();

            $tour               = new Tour();
            $tour->name         = $request->name;
            $tour->max_person   = $request->max_person;
            $tour->is_active    = $request->is_active;
            $tour->save();

            $tour->locations()->attach($request->locations);
            $this->setPrice($tour, $request);

            foreach ($request->validated('description') as $lang => $description) {
                $this->setTranslations($tour, $lang, 'description', $description);
            }

            foreach ($request->validated('meta') as $lang => $meta) {
                $this->setTranslations($tour, $lang, 'meta', $meta);
            }

            DB::commit();

            return $this->successStore();

        } catch (\Exception $e) {
            
            DB::rollBack();

            return $this->errorServer($e->getMessage());
        }
    }

    public function update(TourRequest $request, Tour $tour)
    {
        try {
            DB::beginTransaction();

            $tour->name         = $request->name;
            $tour->max_person   = $request->max_person;
            $tour->is_active    = $request->is_active;
            $tour->save();

            $tour->locations()->attach($request->locations);
            $this->setPrice($tour, $request);

            foreach ($request->validated('description') as $lang => $description) {
                $this->setTranslations($tour, $lang, 'description', $description);
            }

            foreach ($request->validated('meta') as $lang => $meta) {
                $this->setTranslations($tour, $lang, 'meta', $meta);
            }

            DB::commit();

            return $this->successResponse();

        } catch (\Exception $e) {
            
            DB::rollBack();

            return $this->errorServer($e->getMessage());
        }
    }

    public function destroy(Tour $tour)
    {
        try {
            DB::beginTransaction();

            $tour = $tour->load([
                'images'
            ]);

            foreach ($tour->images as $image) {
                $imageService = new ImageService($image->imagePath());
                $imageService->delete($image->image);
                $image->delete();
            }

            $tour->locations()->detach();
            $tour->translations()->delete();
            $tour->variants()->delete();
            $tour->delete();

            DB::commit();

            return $this->successResponse();

        } catch (\Throwable $th) {
            
            DB::rollBack();
            
            throw $th;
        }
    }

    // Private function

    protected function setTranslations(Tour $tour, $lang, $field, $translation)
    {
        $tourTranslation = $tour->translations()->language($lang)->field($field)->firstOrNew();

        $tourTranslation->tour_id      = $tour->id;
        $tourTranslation->language     = $lang;
        $tourTranslation->field        = $field;
        $tourTranslation->translation  = $translation;
        $tourTranslation->save();
    }

    private function setPrice(Tour $tour, $request)
    {
        $price              = $tour->variants()->where('is_default', '=', true)->firstOrNew();
        $price->price       = $request->price;
        $price->tour_id     = $tour->id;
        $price->is_default  = true;
        $price->save();
    }
}

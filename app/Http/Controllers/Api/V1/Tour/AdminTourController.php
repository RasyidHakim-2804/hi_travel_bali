<?php

namespace App\Http\Controllers\Api\V1\Tour;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Tour\TourRequest;
use App\Http\Resources\Tour\AdminTourResource;
use App\Models\Tour;
use App\Models\TourVariant;
use App\Models\TourVariantTranslation;
use App\Services\Images\ImageService;
use App\Traits\JsonResponsesTrait;
use Illuminate\Support\Facades\DB;

class AdminTourController extends Controller
{
    use JsonResponsesTrait;

    public function index()
    {
        return AdminTourResource::collection(
            Tour::orderBy('id', 'DESC')
            ->selectThumbnailVariantPrice()
            ->paginate(25)
        );
    }

    public function show(Tour $tour)
    {
        return $this->successGet(
            new AdminTourResource(
                $tour->load([
                    'locations',
                    'translations',
                    'defaultVariant',
                    'variants'  => fn($query) => $query->where('is_default', '=', false),
                    'variants.translations'
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
            $tour->is_active    = $request->is_active;
            $tour->is_varied    = $request->is_varied;
            $tour->save();

            $tour->locations()->attach($request->locations);

            foreach ($request->validated('description') as $lang => $description) {
                $this->setTranslations($tour, $lang, 'description', $description);
            }

            foreach ($request->validated('meta') as $lang => $meta) {
                $this->setTranslations($tour, $lang, 'meta', $meta);
            }

            if ($request->is_varied) {
                $this->setVariants($tour, $request);
            } else {
                $this->setDefaultVariant($tour, $request);
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
            $tour->is_active    = $request->is_active;
            $tour->is_varied    = $request->is_varied;
            $tour->save();

            $tour->locations()->sync($request->locations);

            foreach ($request->validated('description') as $lang => $description) {
                $this->setTranslations($tour, $lang, 'description', $description);
            }

            foreach ($request->validated('meta') as $lang => $meta) {
                $this->setTranslations($tour, $lang, 'meta', $meta);
            }

            if ($request->is_varied) {
                $this->setVariants($tour, $request);
            } else {
                $this->setDefaultVariant($tour, $request);
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

    /////////////////////
    // Private function//
    /////////////////////

    protected function setTranslations(Tour $tour, $lang, $field, $translation)
    {
        $tourTranslation = $tour->translations()->language($lang)->field($field)->firstOrNew();

        $tourTranslation->tour_id      = $tour->id;
        $tourTranslation->language     = $lang;
        $tourTranslation->field        = $field;
        $tourTranslation->translation  = $translation;
        $tourTranslation->save();
    }

    private function setDefaultVariant(Tour $tour, $request)
    {
        $variant              = $tour->variants()->where('is_default', '=', true)->firstOrNew();
        $variant->name        = $tour->name;
        $variant->max_person  = $request->max_person;
        $variant->price       = $request->price;
        $variant->tour_id     = $tour->id;
        $variant->is_default  = true;
        $variant->save();

        TourVariantTranslation::whereRelation('tourVariant', 'tour_id', '=', $tour->id)->delete();

        $tour->variants()->where('is_default', '=', false)->delete();
    }

    private function setVariants(Tour $tour, $request)
    {
        $unDeletesId = [];

        foreach ($request->variants as $requestVariant) {

            if ( isset($requestVariant['id']) ) {
                $variant = $tour->variants()->where('id', '=', $requestVariant['id'])->firstOrNew();
            } else {
                $variant = new TourVariant();
            }

            $variant->name        = $requestVariant['name'];
            $variant->max_person  = $requestVariant['max_person'];
            $variant->price       = $requestVariant['price'];
            $variant->tour_id     = $tour->id;
            $variant->is_default  = false;
            $variant->save();

            foreach ($requestVariant['descriptions'] as $lang => $description) {
                $this->setVariantTranslations($variant, $lang, 'description', $description);
            }

            $unDeletesId[] = $variant->id;
        }

        TourVariantTranslation::whereHas('tourVariant', function($query) use($tour, $unDeletesId) {
            $query->where('tour_id', '=', $tour->id)->whereNotIn('id', $unDeletesId);
        })->delete();

        $tour->variants()->whereNotIn('id', $unDeletesId)->delete();
    }

    public function setVariantTranslations(TourVariant $variant, $lang, $field, $translation)
    {
        $variantTranslation = $variant->translations()->language($lang)->field($field)->firstOrNew();

        $variantTranslation->tour_variant_id    = $variant->id;
        $variantTranslation->language           = $lang;
        $variantTranslation->field              = $field;
        $variantTranslation->translation        = $translation;
        $variantTranslation->save();
    }
}

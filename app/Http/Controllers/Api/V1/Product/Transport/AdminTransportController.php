<?php

namespace App\Http\Controllers\Api\V1\Product\Transport;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Product\Transport\CreateTransportRequest;
use App\Http\Requests\Api\V1\Product\Transport\UpdateTransportRequest;
use App\Http\Resources\Api\V1\Common\SuccessResource;
use App\Http\Resources\Api\V1\Product\ProductResource;
use App\Models\Image\ProductImage;
use App\Models\Product;
use App\Service\Services\ImageServices\ImageService;
use Illuminate\Support\Facades\DB;

class AdminTransportController extends Controller
{
    public function index()
    {
        $transports = Product::transport()->paginate(25);

        return ProductResource::collection($transports);
    }

    public function show($slug)
    {
        $transport = Product::with(['facilities', 'thumbnailImage'])
                ->transport($slug)
                ->firstOrFail();

        return new ProductResource($transport);
    }

    public function store(CreateTransportRequest $request)
    {
        try {
            DB::beginTransaction();

            $transport                = new Product();
            $transport->name          = $request->name;
            $transport->type          = $transport::TRANSPORT_TYPE;
            $transport->idr_price     = $request->idr_price;
            $transport->price_per_km  = $request->price_per_km;
            $transport->max_person    = $request->max_person;
            $transport->save();

            $transport->facilities()->attach($request->facilities);

            $imageService = $this->uploadImage($request->image, $transport);

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

    public function update(UpdateTransportRequest $request, $slug)
    {
        try {
            DB::beginTransaction();

            $transport = Product::transport($slug)->firstOrFail();

            $transport->name          = $request->name;
            $transport->idr_price     = $request->idr_price;
            $transport->price_per_km  = $request->price_per_km;
            $transport->max_person    = $request->max_person;
            $transport->is_active     = $request->is_active;

            $transport->save();

            $transport->facilities()->sync($request->facilities);

            if ($request->has('image')) {
                $imageService = $this->uploadImage($request->image, $transport);
            }

            DB::commit();

            return new SuccessResource(200);

        } catch (\Throwable $th) {

            DB::rollBack();

            if (isset($imageService)) {
                $imageService->rollback();
            }
            
            throw $th;
        }
    }

    public function destroy($slug)
    {
        try {
            DB::beginTransaction();

            $transport      = Product::transport($slug)->firstOrFail();
            $thumbnailImage = $transport->thumbnailImage;

            if ($thumbnailImage != null) {
                $imageService = new ImageService($thumbnailImage);
                $imageService->delete();
            }

            $transport->thumbnailImage()->delete();
            $transport->facilities()->detach();
            $transport->delete();

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

    private function uploadImage($image, Product $transport)
    {
        $transportImage             = $transport->thumbnailImage()->firstOrNew();
        $imageService               = new ImageService($transportImage);
        
        $transportImage->product_id = $transport->id;
        $transportImage->path       = $imageService->update($image,'transport')->path;
        $transportImage->position   = 1;
        $transportImage->is_saved   = true;
        $transportImage->save();

        return $imageService;
    }

}

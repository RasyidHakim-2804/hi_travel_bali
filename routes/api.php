<?php

use App\Http\Controllers\Api\V1\Facility\AdminFacilityController;
use App\Http\Controllers\Api\V1\Location\AdminLocationController;
use App\Http\Controllers\Api\V1\Product\Transport\AdminTransportController;
use App\Http\Controllers\Api\V1\Product\Tour\AdminTourController;

use App\Http\Controllers\Api\V1\Product\Tour\AdminTourImageController;
use App\Http\Controllers\Api\V1\Product\Tour\TourController;
use App\Http\Controllers\Api\V1\Product\Transport\TransportController;

use App\Http\Controllers\Api\V1\Admin\AdminController;
use App\Http\Controllers\Api\V1\Admin\AdminProfileController;
use App\Http\Controllers\Api\V1\Admin\AuthAdminController;
use App\Http\Controllers\Api\V1\Admin\AdminEmailChangesVerificationController;
use App\Http\Controllers\Api\V1\Admin\AdminFeatureController;
use App\Http\Controllers\Api\V1\Admin\ForgetAdminPasswordController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('/v1')->middleware('verifyLanguage')->group(function () {

    Route::prefix('/admins')->group(function () {

        // Admin - Login
        Route::post('/login', [AuthAdminController::class, 'login']);
        Route::post('/resend-email-verification', [AuthAdminController::class, 'resendEmailVerification'])->middleware('throttle:limit_public_api_with_email_sending');
        Route::post('/email-verification/{token}', [AuthAdminController::class, 'emailVerification']);

        // Admin - Forget Password
        Route::post('/forget-password', [ForgetAdminPasswordController::class, 'forgetPassword'])->middleware('throttle:limit_public_api_with_email_sending');
        Route::post('/forget-password/verify-pin', [ForgetAdminPasswordController::class, 'verifyPin']);
        Route::post('/forget-password/reset-password', [ForgetAdminPasswordController::class, 'resetPassword']);
        Route::post('/verify-email-changes/{token}', AdminEmailChangesVerificationController::class);

        Route::group(['middleware' => ['auth:api_admin', 'scopes:admin', 'verifyUserActive']], function(){

            //Location
            Route::get('/locations', [AdminLocationController::class, 'index']);
            Route::post('/locations', [AdminLocationController::class, 'store']);
            Route::get('/locations/{location:slug}', [AdminLocationController::class, 'show']);
            Route::put('/locations/{location:slug}', [AdminLocationController::class, 'update']);
            Route::delete('/locations/{location:slug}', [AdminLocationController::class, 'destroy']);

            //Facility
            Route::get('/facilities', [AdminFacilityController::class, 'index']);
            Route::post('/facilities', [AdminFacilityController::class, 'store']);
            Route::get('/facilities/{facility}', [AdminFacilityController::class, 'show']);
            Route::put('/facilities/{facility}', [AdminFacilityController::class, 'update']);
            Route::delete('/facilities/{facility}', [AdminFacilityController::class, 'destroy']);

            //Transport Product
            Route::get('/transports', [AdminTransportController::class, 'index']);
            Route::post('/transports', [AdminTransportController::class, 'store']);
            Route::get('/transports/{slug}', [AdminTransportController::class, 'show']);
            Route::put('/transports/{slug}', [AdminTransportController::class, 'update']);
            Route::delete('/transports/{slug}', [AdminTransportController::class, 'destroy']);

            //Tour Product
            Route::get('/tours', [AdminTourController::class, 'index']);
            Route::post('/tours', [AdminTourController::class, 'store']);
            Route::get('/tours/{slug}', [AdminTourController::class, 'show']);
            Route::put('/tours/{slug}', [AdminTourController::class, 'update']);
            Route::delete('/tours/{slug}', [AdminTourController::class, 'destroy']);

            //Tour Image
            Route::get('/products/{slug}/images', [AdminTourImageController::class, 'index']);
            Route::post('/products/{slug}/images', [AdminTourImageController::class,'store']);
            Route::post('/products/{slug}/save-images', [AdminTourImageController::class, 'saveImages']);
            Route::delete('/products/{slug}/delete', [AdminTourImageController::class, 'delete']);

            // Admin - Logout
            Route::post('/logout', [AuthAdminController::class, 'logout']);

            // Admin - Profile
            Route::get('/profile', [AdminProfileController::class, 'index']);
            Route::put('/profile', [AdminProfileController::class, 'update']);
            Route::post('/change-password', [AdminProfileController::class, 'changePassword']);

            // Admin - Feature
            Route::get('/admin-features', [AdminFeatureController::class, 'index']);

            // Admin - Admin
            Route::get('', [AdminController::class, 'index']);
            Route::get('/{admin}', [AdminController::class, 'show']);
            Route::post('', [AdminController::class, 'store']);
            Route::put('/{admin}', [AdminController::class, 'update']);
            Route::delete('/{admin}', [AdminController::class, 'destroy']);
        });
    });

    //Transports
    Route::post('/transports', [TransportController::class, 'list']);

    //Tour
    Route::post('/tours', [TourController::class, 'list']);
    Route::get('/tours/{slug}', [TourController::class, 'show']);
    Route::get('/popular-tours', [TourController::class, 'popular']);
});

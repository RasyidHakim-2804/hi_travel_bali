<?php

use App\Http\Controllers\Api\V1\Admin\AdminAdminController;
use App\Http\Controllers\Api\V1\Admin\AdminUserController;
use App\Http\Controllers\Api\V1\Auth\Admin\AdminAuthController;
use App\Http\Controllers\Api\V1\Auth\Admin\AdminPasswordController;
use App\Http\Controllers\Api\V1\Profile\AdminProfileController;
use App\Http\Controllers\Api\V1\Facility\AdminFacilityController;
use App\Http\Controllers\Api\V1\Location\AdminLocationController;
use App\Http\Controllers\Api\V1\Product\Transport\AdminTransportController;
use App\Http\Controllers\Api\V1\Product\Tour\AdminTourController;

use App\Http\Controllers\Api\V1\Auth\User\UserAuthController;
use App\Http\Controllers\Api\V1\Auth\User\UserPasswordController;
use App\Http\Controllers\Api\V1\Product\Tour\AdminTourImageController;
use App\Http\Controllers\Api\V1\Product\Tour\TourController;
use App\Http\Controllers\Api\V1\Product\Transport\TransportController;
use App\Http\Controllers\Api\V1\Profile\UserProfileController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function(){
    //Admin routes
    Route::prefix('admins')->group(function (){

        //Authentication
        Route::post('/email-verify/confirm/{token}', [AdminAuthController::class, 'verifyEmail']);
        Route::post('/email-verify/resend', [AdminAuthController::class, 'resendEmailVerification']);
        Route::post('/sign-in', [AdminAuthController::class, 'login']);

        //password controller
        Route::post('/password/forget', [AdminPasswordController::class, 'forgetPassword']);
        Route::post('/password/forget/verify-otp', [AdminPasswordController::class, 'verifyOtp']);
        Route::post('/password/reset', [AdminPasswordController::class, 'resetPassword']);
        Route::get('/password/forget/resend', [AdminPasswordController::class, 'forgetPassword']);
        
        Route::middleware(['auth:sanctum', 'verified', 'abilities:is_admin'])->group(function(){
            
            //Authentication
            Route::post('/logout', [AdminAuthController::class, 'logout']);            
            
            //password controller
            Route::post('/password/change', [AdminPasswordController::class, 'changePassword']);
            
            //Profile
            Route::get('/profile', [AdminProfileController::class,'show']);
            Route::put('/profile', [AdminProfileController::class, 'update']);

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
            

            //User
            Route::get('/users', [AdminUserController::class, 'index']);
            Route::get('/users/{user}', [AdminUserController::class, 'show']);
            Route::put('/users/{user}', [AdminUserController::class, 'update']);
                        
            //Admin
            Route::post('/', [AdminAdminController::class, 'store'])->middleware('ability:admin_master');
            Route::get('/', [AdminAdminController::class,'index']);
            Route::put('/{admin}', [AdminAdminController::class, 'update'])->middleware('ability:admin_master');
            Route::get('/{admin}',[AdminAdminController::class, 'show']);
        });

        
    });
    
    //User routes
    Route::prefix('users')->middleware('language')->group(function(){

        //Authentication
        Route::post('/register', [UserAuthController::class,'register']);
        Route::post('/email-verify/confirm/{token}', [UserAuthController::class, 'verifyEmail']);
        Route::post('/email-verify/resend', [UserAuthController::class, 'resendEmailVerification']);
        Route::post('/sign-in', [UserAuthController::class, 'login']);

        //password controller
        Route::post('/password/forget', [UserPasswordController::class, 'forgetPassword']);
        Route::post('/password/forget/verify-otp', [UserPasswordController::class, 'verifyOtp']);
        Route::post('/password/reset/', [UserPasswordController::class, 'resetPassword']);
        Route::get('/password/forget/resend', [UserPasswordController::class,'forgetPassword']);
    

        Route::middleware(['auth:sanctum','verified', 'ability:is_user'])->group(function(){

            //Authentication
            Route::post('/logout', [UserAuthController::class, 'logout']);

            //password controller
            Route::post('/password/change', [UserPasswordController::class, 'changePassword']);

            //User Profile
            Route::get('/profile', [UserProfileController::class,'show']);
            Route::put('/profile', [UserProfileController::class, 'update']);
            
        });
    });
    
    Route::middleware('language')->group(function(){

        //Transports
        Route::post('/transports', [TransportController::class, 'list']);

        //Tour
        Route::post('/tours', [TourController::class, 'list']);
        Route::get('/tours/{slug}', [TourController::class, 'show']);
        Route::get('/popular-tours', [TourController::class, 'popular']);
    });
});

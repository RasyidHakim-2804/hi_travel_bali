<?php

use App\Http\Controllers\Api\V1\Facility\AdminFacilityController;
use App\Http\Controllers\Api\V1\Location\AdminLocationController;
use App\Http\Controllers\Api\V1\Product\Car\AdminCarController;
use App\Http\Controllers\Api\V1\Product\Tour\AdminTourController;
use App\Http\Controllers\Api\V1\Driver\AdminDriverController;

use App\Http\Controllers\Api\V1\Product\Tour\AdminTourImageController;
use App\Http\Controllers\Api\V1\Product\Tour\TourController;
use App\Http\Controllers\Api\V1\Product\Car\CarController;

use App\Http\Controllers\Api\V1\Admin\AdminController;
use App\Http\Controllers\Api\V1\Admin\AdminEmailChangesVerificationController;
use App\Http\Controllers\Api\V1\Admin\AdminFeatureController;
use App\Http\Controllers\Api\V1\Admin\AdminProfileController;
use App\Http\Controllers\Api\V1\Admin\AuthAdminController;
use App\Http\Controllers\Api\V1\Admin\ForgetAdminPasswordController;
use App\Http\Controllers\Api\V1\ArticleCategory\AdminArticleCategoryController;
use App\Http\Controllers\Api\V1\Article\AdminArticleController;
use App\Http\Controllers\Api\V1\Article\AdminArticleImagesController;
use App\Http\Controllers\Api\V1\Article\ArticleCommentController;
use App\Http\Controllers\Api\V1\Auth\User\UserAuthController;
use App\Http\Controllers\Api\V1\Auth\User\UserPasswordController;

use App\Http\Controllers\Api\V1\Article\ArticleController;
use App\Http\Controllers\Api\V1\FastBoatOrder\AdminFastBoatOrderController;
use App\Http\Controllers\Api\V1\FastBoatOrder\FastBoatOrderRefundController;
use App\Http\Controllers\Api\V1\Location\LocationController;
use App\Http\Controllers\Api\V1\FastBoatOrder\UserFastBoatOrderController;
use App\Http\Controllers\Api\V1\PrivateBoatOrder\UserPrivateBoatOrderController;
use App\Http\Controllers\Api\V1\Promo\AdminPromoController;
use App\Http\Controllers\Api\V1\Promo\UserPromoController;
use App\Http\Controllers\Api\V1\User\AdminUserController;
use App\Http\Controllers\Api\V1\User\Auth\AuthUserController;
use App\Http\Controllers\Api\V1\User\ForgetPasswordController;
use App\Http\Controllers\Api\V1\User\UserProfileController;
use App\Http\Controllers\Api\V1\Webhook\Xendit\XenditInvoiceWebhookController;
use App\Http\Controllers\Api\V1\Webhook\Xendit\XenditPayoutLinkWebhookController;


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

     // Webhook
     Route::post('/xendit-webhook/invoice', XenditInvoiceWebhookController::class);
     Route::post('/xendit-webhook/payout-link', XenditPayoutLinkWebhookController::class); 

    // Public - Article
    Route::get('/top-articles', [ArticleController::class, 'top']);
    Route::resource('/articles', ArticleController::class, ['only' => ['index', 'show']]);
    Route::post('/articles/{article}/comments', [ArticleCommentController::class, 'store']);

    //Cars
    Route::post('/transports', [CarController::class, 'list']);

    //Tour
    Route::post('/tours', [TourController::class, 'list']);
    Route::get('/tours/{slug}', [TourController::class, 'show']);
    Route::get('/popular-tours', [TourController::class, 'popular']);

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

            // Admin - Logout
            Route::post('/logout', [AuthAdminController::class, 'logout']);

            // Admin - Profile
            Route::get('/profile', [AdminProfileController::class, 'index']);
            Route::put('/profile', [AdminProfileController::class, 'update']);
            Route::post('/change-password', [AdminProfileController::class, 'changePassword']);

            // Admin - Promo
            Route::resource('/promos', AdminPromoController::class);

            // Admin - Article
            Route::resource('/articles', AdminArticleController::class);
            Route::post('/article-images', [AdminArticleImagesController::class, 'store']);

            // Admin - Article Category
            Route::resource('/article-categories', AdminArticleCategoryController::class);

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

            // Driver
            Route::get('/drivers', [AdminDriverController::class, 'index']);
            Route::get('/drivers-all', [AdminDriverController::class, 'all']);
            Route::get('/drivers/{driver}', [AdminDriverController::class, 'show']);
            Route::post('/drivers', [AdminDriverController::class, 'store']);
            Route::put('/drivers/{driver}', [AdminDriverController::class, 'update']);
            Route::delete('/drivers/{driver}', [AdminDriverController::class, 'destroy']);

            //Car Product
            Route::get('/transports', [AdminCarController::class, 'index']);
            Route::post('/transports', [AdminCarController::class, 'store']);
            Route::get('/transports/{slug}', [AdminCarController::class, 'show']);
            Route::put('/transports/{slug}', [AdminCarController::class, 'update']);
            Route::delete('/transports/{slug}', [AdminCarController::class, 'destroy']);

            //Tour Product
            Route::get('/tours', [AdminTourController::class, 'index']);
            Route::post('/tours', [AdminTourController::class, 'store']);
            Route::get('/tours/{slug}', [AdminTourController::class, 'show']);
            Route::put('/tours/{slug}', [AdminTourController::class, 'update']);
            Route::delete('/tours/{slug}', [AdminTourController::class, 'destroy']);

            //Tour Image
            Route::get('/products/{slug}/images', [AdminTourImageController::class, 'index']);
            Route::post('/products/{slug}/images', [AdminTourImageController::class,'store']);
            Route::post('/products/{slug}/save-images', [AdminTourImageController::class, 'save']);

            // Admin - FastBoat Order
            Route::get('/fast-boat-orders', [AdminFastBoatOrderController::class, 'index']);
            Route::get('/fast-boat-orders/{order}', [AdminFastBoatOrderController::class, 'show']);

            // Admin - Promo
            Route::resource('/promos', AdminPromoController::class);

            // Admin - User
            Route::get('/users', [AdminUserController::class, 'index']);
            Route::put('/users/{user:uuid}', [AdminUserController::class, 'update']);

            // Admin - Feature
            Route::get('/admin-features', [AdminFeatureController::class, 'index']);

            // Admin - Admin
            Route::get('/', [AdminController::class, 'index']);
            Route::get('/{admin}', [AdminController::class, 'show']);
            Route::post('', [AdminController::class, 'store']);
            Route::put('/{admin}', [AdminController::class, 'update']);
            Route::delete('/{admin}', [AdminController::class, 'destroy']);
        });
    });

    //User routes
    Route::prefix('/users')->group(function(){

        //Authentication
        Route::post('/register', [AuthUserController::class,'register']);
        Route::post('/resend-email-verification', [AuthUserController::class, 'resendEmailVerification'])->middleware('throttle:limit_public_api_with_email_sending');
        Route::post('/email-verification/{token}', [AuthUserController::class, 'emailVerification']);
        Route::post('/login', [AuthUserController::class, 'login']);

        //password controller
        Route::post('/password/forget', [ForgetPasswordController::class, 'forgetPassword']);
        Route::post('/password/forget/verify-otp', [ForgetPasswordController::class, 'verifyPin']);
        Route::post('/password/reset/', [ForgetPasswordController::class, 'resetPassword']);
        Route::get('/password/forget/resend', [ForgetPasswordController::class,'forgetPassword']);

        Route::middleware(['auth:api_user'])->group(function(){
            //User Profile
            Route::get('/profile', [UserProfileController::class, 'index']);
            Route::put('/profile', [UserProfileController::class, 'update']);
            Route::post('/change-password', [UserProfileController::class, 'changePassword']);
            Route::post('/change-language', [UserProfileController::class, 'changeLanguage']);

            // Promo
            Route::post('/apply-promo', UserPromoController::class);
        });
    
        Route::prefix('/{user}')->scopeBindings()->group(function () {
            Route::middleware(['auth:api_user'])->group(function(){

                //Authentication
                Route::post('/logout', [AuthUserController::class, 'logout']);

                //password controller
                Route::post('/password/change', [UserPasswordController::class, 'changePassword']);

                // Order
                Route::get('/fast-boat-orders', [UserFastBoatOrderController::class, 'index']);
                Route::get('/fast-boat-orders/{fastBoatOrder}', [UserFastBoatOrderController::class, 'show']);
                Route::post('/fast-boat-orders', [UserFastBoatOrderController::class, 'store']);
                Route::post('/fast-boat-orders/{fastBoatOrder}/cancel', [UserFastBoatOrderController::class, 'cancel']);
                Route::post('/fast-boat-orders/{fastBoatOrder}/refund-request', [FastBoatOrderRefundController::class, 'request']);
                Route::post('/fast-boat-orders/{fastBoatOrder}/refund', [FastBoatOrderRefundController::class, 'refund']);

                Route::get('/private-boat-orders', [UserPrivateBoatOrderController::class, 'index']);
                Route::get('/private-boat-orders/{fastBoatOrder}', [UserPrivateBoatOrderController::class, 'show']);
                Route::post('/private-boat-orders', [UserPrivateBoatOrderController::class, 'store']);
                Route::post('/private-boat-orders/{fastBoatOrder}/cancel', [UserPrivateBoatOrderController::class, 'cancel']);
                Route::post('/private-boat-orders/{fastBoatOrder}/refund', [UserPrivateBoatOrderController::class, 'refund']);
            });
        });
    });
});

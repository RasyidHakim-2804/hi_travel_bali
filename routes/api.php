<?php

use App\Http\Controllers\Api\V1\Admin\AdminAdminController;
use App\Http\Controllers\Api\V1\Admin\AdminUserController;
use App\Http\Controllers\Api\V1\Auth\Admin\AdminAuthController;
use App\Http\Controllers\Api\V1\Auth\Admin\AdminPasswordController;
use App\Http\Controllers\Api\V1\ProductCategory\AdminProductCategoryController;
use App\Http\Controllers\Api\V1\ArticleCategory\AdminArticleCategoryController;
use App\Http\Controllers\Api\V1\Contact\AdminContactController;
use App\Http\Controllers\Api\V1\Product\AdminProductController;
use App\Http\Controllers\Api\V1\Product\AdminProductImageController;
use App\Http\Controllers\Api\V1\Profile\AdminProfileController;
use App\Http\Controllers\Api\V1\ArticleAuthor\AdminArticleAuthorController;
use App\Http\Controllers\Api\V1\Article\AdminArticleController;
use App\Http\Controllers\Api\V1\Subscriber\AdminSubscriberController;

use App\Http\Controllers\Api\V1\Auth\User\UserAuthController;
use App\Http\Controllers\Api\V1\Auth\User\UserPasswordController;
use App\Http\Controllers\Api\V1\Profile\UserProfileController;
use App\Http\Controllers\Api\V1\Wishlist\UserWishlistController;

use App\Http\Controllers\Api\V1\ProductCategory\ProductCategoryController;
use App\Http\Controllers\Api\V1\Contact\ContactController;
use App\Http\Controllers\Api\V1\Product\ProductController;
use App\Http\Controllers\Api\V1\Article\ArticleController;
use App\Http\Controllers\Api\V1\Subscriber\SubscriberController;
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
            
        });
    });
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Auth\SocialLite\FacebookController;
use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\BusinessCategoryController;
use App\Models\BusinessCategory;
use Laravel\Passport\Passport;

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

Route::prefix('v1')->group(function () {
    // Authentication Routes

    Route::prefix('auth')->group(function () {

        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/registeruser', [AuthController::class, 'registerUser'])->name('ruser');
        Route::post('/register_admin', [AuthController::class, 'registerAdmin'])->name('radmin');
        Route::post('/register_super_admin', [AuthController::class, 'registerSuperAdmin'])->name('rsadmin');
        Route::post('/verify_otp', [AuthController::class, 'verifyOtp'])->name('verify_otp');

        // tokens
        Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        // get access token
        Route::post('/access_token', [AuthController::class, 'getAccessToken'])->name('token');

    });

    Route::prefix('socialite')->group(function () {
        Route::get('facebook', [FacebookController::class, 'redirectToFacebook'])->name('auth.facebook');
        Route::get('facebook/callback', [FacebookController::class, 'handleFacebookCallback'])->
            name('auth.facebook.callback');
    });

    //user routes

    //Auth routes
    Route::group(['middleware' => ['auth:api']], static function () {

        Route::prefix('users')->group(function () {

            Route::get('/', [UserController::class, 'index'])->name('user.index');
            Route::get('/{id}', [UserController::class, 'getUserById'])->name('user.show');
            Route::post('/{id}', [UserController::class, 'store'])->name('user.store');
            Route::patch('/{id}', [UserController::class, 'update'])->name('update_profile');
            Route::post('/update_password', [UserController::class, 'updatePassword'])->name('update_password');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('user.destroy');
        });

        Route::prefix('occupational-categories')->group(function () {

            Route::get('/', [BusinessCategoryController::class, 'index'])->name('busCat.index');
            Route::get('/{id}', [BusinessCategoryController::class, 'show'])->name('busCat.show');
            Route::post('/', [BusinessCategoryController::class, 'store'])->name('busCat.store');
            Route::patch('/{id}', [BusinessCategoryController::class, 'update'])->name('busCat.update');
            Route::delete('/{id}', [BusinessCategoryController::class, 'destroy'])->name('busCat.destroy');
        });

    });


});

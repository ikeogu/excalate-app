<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Auth\SocialLite\FacebookController;
use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\BusinessCategoryController;
use App\Http\Controllers\BusinessProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProximityPlanController;
use App\Http\Controllers\UserEmegencyContact;
use App\Http\Controllers\UserProximityPlanController;

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

Route::group(['middleware' => ['cors', 'json.response']], static function () {

    Route::any('/', static fn () => response()->json([
        'message' => 'Welcome to Excalate API',
        'apiVersion' => 'v3.0.0',
    ]));

    Route::prefix('v1')->group(function () {

        Route::prefix('auth')->group(function () {

            Route::post('/login', [AuthController::class, 'login'])->name('login');
            Route::post('/register', [AuthController::class, 'registerUser'])->
                name('register_user');
            Route::post('/register_admin', [AuthController::class, 'registerAdmin'])->
                name('register_admin');
            Route::post('/register_super_admin', [AuthController::class, 'registerSuperAdmin'])->
                name('register_super_admin');
            Route::post('/otp/verify_otp', [AuthController::class, 'verifyOtp'])->
                name('verify_otp');

            Route::group(['middleware' => ['auth:api']], function () {
            // tokens
                Route::post('/refresh', [AuthController::class, 'refresh'])->
                    name('refresh');
                Route::delete('logout', [AuthController::class, 'logout']);
            });
            // get access token
            Route::post('/access_token', [AuthController::class, 'getAccessToken'])->
                name('token');
        // Authentication Routes

        });

        Route::prefix('socialite')->group(function () {
            Route::get('facebook', [FacebookController::class, 'redirectToFacebook'])->
                name('auth.facebook');
            Route::get('facebook/callback',
                [FacebookController::class, 'handleFacebookCallback'])->
                name('auth.facebook.callback');
        });

        //user routes

        //Auth routes

        Route::group(['middleware' => ['auth:api']],
             static function () {

            Route::prefix('users')->group(function () {
                Route::get('/', [UserController::class, 'index'])->
                    name('user.index');

                Route::get('/{id}', [UserController::class, 'getUserById'])->
                    name('user.show');
                Route::patch('/{id}', [UserController::class, 'update']);
                Route::post('/update_password', [UserController::class, 'updatePassword'])->
                    name('update_password');
                Route::delete('/{id}', [UserController::class, 'destroy'])->
                    name('user.destroy');
            });

            Route::apiResource('user/business-profile', BusinessProfileController::class);
            Route::apiResource('proximity-plans',ProximityPlanController::class);
            Route::apiResource('business-categories',BusinessCategoryController::class);
            Route::apiResource('user-proximity-plans',UserProximityPlanController::class);
            Route::apiResource('emergency-contacts', ContactController::class);

                //shallow nested routes
            Route::resource('user.business-profile', BusinessProfileController::class)->shallow()->
                only(['index', 'store']);
            Route::resource('user.emergency-contacts', UserEmegencyContact::class)->shallow()->
                only(['index', 'store']);


        });

    });
});

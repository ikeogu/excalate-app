<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Auth\SocialLite\FacebookController;
use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\BusinessCategoryController;
use App\Http\Controllers\BusinessProfileController;
use App\Http\Controllers\ContactController;


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

            Route::post('/login', [AuthController::class, 'login']);
            Route::post('/register', [AuthController::class, 'registerUser'])->name('ruser');
            Route::post('/register_admin', [AuthController::class, 'registerAdmin'])->name('radmin');
            Route::post('/register_super_admin', [AuthController::class, 'registerSuperAdmin'])->name('rsadmin');
            Route::post('/verify_otp', [AuthController::class, 'verifyOtp'])->name('verify_otp');

            Route::group(['middleware' => ['auth:sanctum']], function () {
            // tokens
                Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
                Route::delete('logout', [AuthController::class, 'logout']);
            });
            // get access token
            Route::post('/access_token', [AuthController::class, 'getAccessToken'])->name('token');
        // Authentication Routes

        });

        Route::prefix('socialite')->group(function () {
            Route::get('facebook', [FacebookController::class, 'redirectToFacebook'])->name('auth.facebook');
            Route::get('facebook/callback', [FacebookController::class, 'handleFacebookCallback'])->
                name('auth.facebook.callback');
        });

        //user routes

        //Auth routes
        Route::group(['middleware' => ['auth:sanctum', 'ability:admin']], function () {

            Route::prefix('admin')->group(function(){

                Route::prefix('users')->group(function () {

                    Route::get('/', [UserController::class, 'index'])->name('user.index');
                    Route::get('/{id}', [UserController::class, 'getUserById']);
                    Route::post('/{id}', [UserController::class, 'store']);
                    Route::patch('/{id}', [UserController::class, 'update']);
                    Route::post('/update_password', [UserController::class, 'updatePassword']);
                    Route::delete('/{id}', [UserController::class, 'destroy']);
                });

                Route::prefix('occupational-categories')->group(function () {

                    Route::get('/', [BusinessCategoryController::class, 'index'])->name('busCat.index');
                    Route::get('/{id}', [BusinessCategoryController::class, 'show'])->name('busCat.show');
                    Route::post('/', [BusinessCategoryController::class, 'store'])->name('busCat.store');
                    Route::patch('/{id}', [BusinessCategoryController::class, 'update'])->name('busCat.update');
                    Route::delete('/{id}', [BusinessCategoryController::class, 'destroy'])->name('busCat.destroy');
                });

            });
            //users routes
        });

        Route::group(['middleware' => ['auth:sanctum', 'abilities:admin,user']], static function () {

            Route::prefix('user')->group(function () {

                Route::get('/{id}', [UserController::class, 'getUserById'])->name('user.show');
                Route::patch('/{id}', [UserController::class, 'update']);
                Route::post('/update_password', [UserController::class, 'updatePassword'])->name('update_password');
                Route::delete('/{id}', [UserController::class, 'destroy'])->name('user.destroy');
            });

            Route::prefix('business-profile')->group(function () {

                Route::get('/', [BusinessProfileController::class, 'index'])->name('busProf.index');
                Route::get('/{id}', [BusinessProfileController::class, 'show'])->name('busProf.show');
                Route::post('/', [BusinessProfileController::class, 'store'])->name('busProf.store');
                Route::patch('/{id}', [BusinessProfileController::class, 'update'])->name('busProf.update');
                Route::delete('/{id}', [BusinessProfileController::class, 'destroy'])->name('busProf.destroy');
            });

            Route::prefix('contact')->group(function () {

                Route::get('/', [ContactController::class, 'index'])->name('contact.index');
                Route::get('/{id}', [ContactController::class, 'show'])->name('contact.show');
                Route::post('/', [ContactController::class, 'store'])->name('contact.store');
                Route::patch('/{id}', [ContactController::class, 'update'])->name('contact.update');
                Route::delete('/{id}', [ContactController::class, 'destroy'])->name('contact.destroy');
            });

        });

    });
});
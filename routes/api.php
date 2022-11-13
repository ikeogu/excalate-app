<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\AuthController;
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

});

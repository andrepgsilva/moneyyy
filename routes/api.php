<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BillsController;
use App\Http\Controllers\API\VerifyEmailController;
use App\Http\Controllers\API\ForgotPasswordController;

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

Route::post('/email-exists', [VerifyEmailController::class, 'index']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'index']);

// Authentication routes
Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register'])->name('user.register');
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth.api');
    Route::post('refresh-token', [AuthController::class, 'refreshToken']);
    
    Route::post('me', [AuthController::class, 'me'])->middleware('auth.api');
});

// Bills routes
Route::group(['middleware' => 'auth.api'], function() {
    Route::get('/bills', [BillsController::class, 'index']);
    Route::post('/bills', [BillsController::class, 'store']);
    Route::get('/bills/{id}', [BillsController::class, 'show']);
    Route::delete('/bills/{id}', [BillsController::class, 'destroy']);
    Route::put('/bills/{id}', [BillsController::class, 'update']);
});
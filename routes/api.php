<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BillsController;
use App\Http\Controllers\API\VerifyEmailController;
use App\Http\Controllers\API\ForgotPassword\TokenMatchController;
use App\Http\Controllers\API\ForgotPassword\ResetPasswordController;
use App\Http\Controllers\API\ForgotPassword\ForgotPasswordController;

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

// Recover password routes
Route::post('/forgot-password', [ForgotPasswordController::class, 'index']);
Route::post('/token-match', [TokenMatchController::class, 'index']);
Route::put('/reset-password', [ResetPasswordController::class, 'index']);

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

// Bills routes for mobile
Route::group(['middleware' => 'auth:api'], function() {
    Route::get('mobile/bills', [BillsController::class, 'index']);
    Route::post('mobile/bills', [BillsController::class, 'store']);
    Route::get('mobile/bills/{id}', [BillsController::class, 'show']);
    Route::delete('mobile/bills/{id}', [BillsController::class, 'destroy']);
    Route::put('mobile/bills/{id}', [BillsController::class, 'update']);
});
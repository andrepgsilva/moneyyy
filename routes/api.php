<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BillsController;
use App\Http\Controllers\API\VerifyEmailController;

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

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('validateJwtToken');
    Route::post('me', [AuthController::class, 'me'])->middleware('auth:api');
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
});

Route::post('/email-exists', [VerifyEmailController::class, 'index']);

// Put routes on middleware
Route::get('/bills', [BillsController::class, 'index']);
Route::post('/bills', [BillsController::class, 'store']);
Route::get('/bills/{id}', [BillsController::class, 'show']);
Route::delete('/bills/{id}', [BillsController::class, 'destroy']);
Route::put('/bills/{id}', [BillsController::class, 'update']);
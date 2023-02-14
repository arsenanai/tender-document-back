<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\PartnerIDController;
use App\Http\Controllers\SubpartnerController;

// use App\Http\Controllers\API\PartnerController;

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

Route::controller(RegisterController::class)->group(function(){
    // Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::post('partner-ids/check', [PartnerIDController::class, 'check']);
        
Route::middleware('auth:sanctum')->group( function () {
    Route::apiResources([
        'partner-ids' => PartnerIDController::class,
        'subpartners' => SubpartnerController::class,
        'partners' => PartnerController::class,
    ]);
    Route::post('logout', [RegisterController::class, 'logout']);
});
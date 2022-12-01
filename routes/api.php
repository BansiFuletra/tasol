<?php

use Illuminate\Http\Request;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::post('/login', [\App\Http\Controllers\api\AuthController::class, 'login']);
Route::group([
    'middleware' => 'auth:api'
], function ($router){

    Route::post('/logout', [\App\Http\Controllers\api\AuthController::class, 'logout']);
    Route::post('/refresh', [\App\Http\Controllers\api\AuthController::class, 'refresh']);
    Route::get('/user-profile', [\App\Http\Controllers\api\AuthController::class, 'userProfile']);
    Route::apiResource('products',\App\Http\Controllers\api\ProductController::class);
    Route::get('/category',[\App\Http\Controllers\api\ProductController::class,'getCategory']);
});

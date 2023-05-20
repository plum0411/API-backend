<?php

use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function () {
    Route::apiResource('posts', 'App\Http\Controllers\Api\PostController');
});

Route::group([
    'prefix' => 'posts'
], function ($router) {
Route::post('anonymousStore', [PostController::class, 'anonymousStore']);
});

Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    Route::post('register', [UserController::class, 'store']);
});

Route::get('/user', [UserController::class, 'getUser'])->middleware('auth:api');
Route::post('/user', [UserController::class, 'store']);
Route::put('/user', [UserController::class, 'update'])->middleware('auth:api');
Route::delete('/user', [UserController::class, 'destroy'])->middleware('auth:api');

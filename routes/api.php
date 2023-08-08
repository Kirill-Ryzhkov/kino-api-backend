<?php

use App\Http\Controllers\AuthController;

use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApiController;

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


Route::post('login', [AuthController::class, 'auth']);

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('user')->group(function () {
        Route::get('', [UserController::class, 'get']);
        Route::put('update', [UserController::class, 'update']);
        Route::post('logout', [UserController::class, 'logout']);
    });

    Route::get('movies', [ApiController::class, 'getMovies']);
    Route::get('movie/{id}', [ApiController::class, 'getMovie']);

    Route::prefix('favorite')->group(function () {
        Route::post('toggle/{id}', [MovieController::class, 'toggleFavorite']);
        Route::get('', [MovieController::class, 'listFavorite']);
    });
});

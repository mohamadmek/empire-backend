<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteController;

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

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group( function () {
    Route::post('/auth/updateUser', [AuthController::class, 'updateUser']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/getUserWithToken', [AuthController::class, 'getUserWithToken']);

    Route::post('/addFavorite', [FavoriteController::class, 'store']);
    Route::post('/deleteFavorite', [FavoriteController::class, 'destroy']);

});

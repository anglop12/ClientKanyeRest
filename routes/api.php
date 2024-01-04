<?php

use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\QuoteController;
use App\Http\Controllers\AuthController;
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

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('passport')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::get('/quote', [QuoteController::class, 'index'])->name('get.quote');
        Route::get('/quotes/{num}', [QuoteController::class, 'indexMulti'])->name('get.quotes');
        Route::get('/favorites', [FavoriteController::class, 'index'])->name('get.favorites');
        Route::post('/favorites', [FavoriteController::class, 'store'])->name('save.favorites');
        Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy'])->name('delete.favorites');
    });
});


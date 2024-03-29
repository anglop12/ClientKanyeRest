<?php

use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\QuoteController;
use App\Http\Controllers\Api\UserController;
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

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('passport')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/editUser/{id}', [AuthController::class, 'editUser']);
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::middleware('throttle:30,1')->get('/quote', [QuoteController::class, 'index'])->name('get.quote');
        Route::get('/quotes/{num}', [QuoteController::class, 'indexMulti'])->name('get.quotes');

        Route::get('/favorites', [FavoriteController::class, 'index'])->name('get.favorites');
        Route::post('/favorites', [FavoriteController::class, 'store'])->name('save.favorites');
        Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy'])->name('delete.favorites');

        Route::get('/users', [UserController::class, 'index'])->name('get.users');
        Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('delete.user');
    });
});


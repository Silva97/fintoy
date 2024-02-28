<?php

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
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


Route::prefix('users')->group(function () {
    Route::post('/', [UserController::class, 'create']);
    Route::post('/login', [UserController::class, 'login']);
});

/**
 * Authenticated routes.
 */
Route::middleware('auth:api')->group(function () {
    Route::prefix('transactions')->group(function () {
        Route::post('/', [TransactionController::class, 'create']);
    });

    Route::prefix('users')->group(function () {
        Route::get('/self', [UserController::class, 'getSelf']);
    });
});

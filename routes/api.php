<?php

use App\Http\Controllers\API\AdminUserController;
use App\Http\Controllers\Auth\AuthController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {

});

// Route::controller('App\Http\Controllers\API\AdminUserController')->group(function () {
//     Route::prefix('admin')->name('admin.')->middleware('auth:sanctum')->group(function () {
//         Route::prefix('/users')->name('users.')->group(function () {
//             Route::get('/', 'index')->name('index');
//             Route::get('/create', 'create')->name('index');
//             Route::get('/{user}/edit', 'edit')->name('edit');
//             Route::post('/', 'store')->name('store');
//             Route::put('/{user}', 'update')->name('update');
//             Route::get('/{user}', 'show')->name('show');
//             Route::delete('/{user}', 'destroy')->name('destroy');
//         });
//     });
// });

Route::controller('App\Http\Controllers\API\AdminUserController')->group(function () {
    Route::prefix('admin')->name('admin.')->middleware('auth:api')->group(function () {
        Route::prefix('/users')->name('users.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('index');
            Route::get('/{user}/edit', 'edit')->name('edit');
            Route::post('/', 'store')->name('store');
            Route::put('/{user}', 'update')->name('update');
            Route::get('/{user}', 'show')->name('show');
            Route::delete('/{user}', 'destroy')->name('destroy');
        });
    });
});

Route::post('login', [AuthController::class, 'login']);
Route::post('refresh-token', [AuthController::class, 'refreshToken']);

Route::get('passport-token', [AuthController::class, 'passportToken']);
Route::delete('logout', [AuthController::class, 'logout'])->middleware('auth:api');

Route::controller(AuthController::class)->group(function () {
    Route::prefix('/')->name('auth.')->middleware('auth:sanctum')->group(function () {
        Route::get('token', 'getToken')->name('token');
    });
});

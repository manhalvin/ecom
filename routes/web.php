<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    });

    // Route Admin: Dashboard
    Route::controller(AdminDashboardController::class)->group(function () {
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::prefix('/dashboard')->name('dashboard.')->group(function () {
                Route::get('/', 'index')->name('index');
            });
        });
    });

// Route Admin: User
    Route::controller(AdminUserController::class)->group(function () {
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::prefix('/users')->name('users.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{user}/edit', 'edit')->name('edit');
                Route::post('/', 'store')->name('store');
                Route::put('/{user}', 'update')->name('update');
                Route::delete('/{user}', 'destroy')->name('destroy');
                Route::get('/list', 'userList')->name('list');
                Route::get('/ajax/{user}/edit', 'userEditAjax')->name('edit_ajax');
            });
        });
    });
});

Auth::routes(['register' => false]);

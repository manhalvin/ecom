<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminPermissionController;
use App\Http\Controllers\Admin\AdminRoleController;
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
                Route::get('/', 'index')->name('index')->middleware('can:list_user');
                Route::get('/{id}/edit', 'edit')->name('edit')->middleware('can:edit_user,id');
                Route::post('/', 'store')->name('store')->middleware('can:add_user');
                Route::put('/{user}', 'update')->name('update');
                Route::delete('/{user}', 'destroy')->name('destroy');
                Route::get('/list', 'userList')->name('list');
                Route::get('/ajax/{user}/edit', 'userEditAjax')->name('edit_ajax');
            });
        });
    });

    // Route Admin: Role
    Route::controller(AdminRoleController::class)->group(function () {
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::prefix('/roles')->name('roles.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                // Route::get('/{role}', 'show')->name('show');
                Route::get('/{id}/edit', 'edit')->name('edit');
                Route::post('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
                Route::get('/list', 'roleList')->name('list');
            });
        });
    });

    // Route Admin: Permission
    Route::controller(AdminPermissionController::class)->group(function () {
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::prefix('/permissions')->name('permissions.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                // Route::get('/{role}', 'show')->name('show');
                Route::get('/{id}/edit', 'edit')->name('edit');
                Route::post('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
                Route::get('/list', 'roleList')->name('list');
            });
        });
    });
});

Auth::routes(['register' => false]);

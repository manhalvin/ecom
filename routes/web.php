<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\API\NexmoSMSController;
use App\Http\Controllers\Admin\AdminRoleController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminGroupsController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminPermissionController;

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
        Route::prefix('admin')->name('admin.')->middleware('can:users')->group(function () {
            Route::prefix('/users')->name('users.')->group(function () {
                Route::get('/', 'index')->name('index')->can('viewAny', User::class);
                // Route::get('/{id}/edit', 'edit')->name('edit')->can('update', User::class);
                // Route::get('/{id}/edit', 'edit')->name('edit')->can('users.edit');
                Route::get('/{user}/edit', 'edit')->name('edit');
                Route::post('/', 'store')->name('store')->can('create', User::class);
                Route::put('/{user}', 'update')->name('update');
                Route::delete('/{user}', 'destroy')->name('destroy');
                Route::get('/list', 'userList')->name('list');
                Route::post('/list', 'userList')->name('list');
                Route::get('/ajax/{user}/edit', 'userEditAjax')->name('edit_ajax');
                Route::get('districts/ajax/{province_id}', 'GetDistricts');
                Route::get('wards/ajax/{province_id}','GetWards');
                Route::post('districts/ajax/{province_id}', 'GetDistricts');
                Route::post('wards/ajax/{province_id}','GetWards');
                Route::post('/resort', 'resort')->name('resort');
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

    // Route Admin: Groups
    Route::controller(AdminGroupsController::class)->group(function () {
        Route::prefix('admin')->name('admin.')->middleware('can:groups')->group(function () {
            Route::prefix('/groups')->name('groups.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{group}', 'show')->name('show');
                Route::get('/{group}/edit', 'edit')->name('edit');
                Route::post('/{group}', 'update')->name('update');
                Route::get('/{group}/delete', 'destroy')->name('destroy');
                Route::get('permission/{group}', 'permission')->name('permission');
                Route::post('permission/{group}', 'postPermission');
            });
        });
    });

});

Route::controller(AuthController::class)->group(function () {
    Route::prefix('/google')->name('google.')->group(function () {
        Route::get('/', 'loginUsingGoogle')->name('login');
        Route::get('/callback', 'callbackFromGoogle')->name('callback');
    });
});

// Auth::routes(['register' => false]);
Auth::routes();

Route::controller(App\Http\Controllers\Auth\AuthOtpController::class)->group(function(){
    Route::get('otp/login', 'login')->name('otp.login');
    Route::post('otp/generate', 'generate')->name('otp.generate');
    Route::get('otp/verification/{user_id}', 'verification')->name('otp.verification');
    Route::post('otp/login', 'loginWithOtp')->name('otp.getlogin');
});

Route::get('sendSMS', [NexmoSMSController::class, 'index']);

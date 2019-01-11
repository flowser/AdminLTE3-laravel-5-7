<?php

use App\Http\Controllers\Auth\Test\TestController;
use App\Http\Controllers\Auth\Superadmin\RoleController;
use App\Http\Controllers\Auth\Superadmin\PermissionController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('{path}', 'HomeController@index')->where('path', '([A-z\d-\/_.]+)?');

// Route::get('permission', [PermissionController::class, 'index'])->name('permission.index');

// Route::resource('permission', 'TestController');
// Route::post('permission', [TestController::class, 'index'])->name('permission.index');
// Route::group(['namespace' => 'Role'], function () {
//     Route::get('role', [TestController::class, 'index'])->name('role.index');
//     Route::get('role/create', [TestController::class, 'create'])->name('role.create');
//     Route::post('role', [TestController::class, 'store'])->name('role.store');

//     Route::group(['prefix' => 'role/{role}'], function () {
//         Route::get('edit', [TestController::class, 'edit'])->name('role.edit');
//         Route::patch('/', [TestController::class, 'update'])->name('role.update');
//         Route::delete('/', [TestController::class, 'destroy'])->name('role.destroy');
//     });
// });


Route::group([ 'middleware'=> 'auth' ], function () {
    /* 
    ['role:super-admin| admin']
     * These routes need view-backend permission
     * (good if you want to allow more than one group in the backend,
     * then limit the backend features by different roles or permissions)
     *
     * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
     * These routes can not be hit if the password is expired
     */
// 
    // Route::group(['namespace' => 'Permission'], function () {
        // Route::get('permission', [PermissionController::class, 'index'])->name('permission.index');
        // Route::get('permission/create', [PermissionController::class, 'create'])->name('permission.create');
        // Route::post('permission', [PermissionController::class, 'store'])->name('permission.store');
    
        // Route::group(['prefix' => 'permission/{permission}'], function () {
        //     Route::get('edit', [PermissionController::class, 'edit'])->name('permission.edit');
        //     Route::patch('/', [PermissionController::class, 'update'])->name('permission.update');
        //     Route::delete('/', [PermissionController::class, 'destroy'])->name('permission.destroy');
        // });
    // });
 
    // Route::group(['namespace' => 'Role'], function () {
        Route::get('role', [RoleController::class, 'index'])->name('role.index');
        Route::get('role/create', [RoleController::class, 'create'])->name('role.create');
        Route::post('role', [RoleController::class, 'store'])->name('role.store');
    
        Route::group(['prefix' => 'role/{role}'], function () {
            Route::get('edit', [RoleController::class, 'edit'])->name('role.edit');
            Route::patch('/', [RoleController::class, 'update'])->name('role.update');
            Route::delete('/', [RoleController::class, 'destroy'])->name('role.destroy');
        });
    // });
});
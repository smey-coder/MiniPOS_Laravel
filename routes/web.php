<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Models\Roles;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\PermissionController;

Route::get('/', function () {
    return view('welcome');
});



/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin', function () {
         return view('layouts.admin.admin_layout');
    });

});

Route::get('/roles', [RoleController::class, 'index']);
/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('verified')->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    //Permission
    Route::get('/permissions', [PermissionController::class,'index'])->name('permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [PermissionController::class,'store'])->name('permissions.store');
    Route::get('/permissions/{id}/edit', [PermissionController::class,'edit'])->name('permissions.edit');
    Route::post('/permissions/{id}', [PermissionController::class,'update'])->name('permissions.update');
    Route::delete('/permissions/{id}', [PermissionController::class,'destroy'])->name('permissions.destroy');

    // Route::resource('roles', ProductController::class);
    // Role
    Route::get('/roles', [RoleController::class,'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class,'store'])->name('roles.store');
    Route::get('/roles/{id}/edit', [RoleController::class,'edit'])->name('roles.edit');
    Route::post('/roles/{id}', [RoleController::class,'update'])->name('roles.update');
    Route::delete('/roles/{id}', [RoleController::class,'destroy'])->name('roles.destroy');
});

require __DIR__.'/auth.php';
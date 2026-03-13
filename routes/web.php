<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Models\Roles;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerControllers;
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

    //Article
    Route::get('/articles', [ArticleController::class,'index'])->name('articles.index');
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class,'store'])->name('articles.store');
    Route::get('/articles/{id}/edit', [ArticleController::class,'edit'])->name('articles.edit');
    Route::post('/articles/{id}', [ArticleController::class,'update'])->name('articles.update');
    Route::delete('/articles/{id}', [ArticleController::class,'destroy'])->name('articles.destroy');

    //User
    Route::get('/users', [UserController::class,'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class,'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class,'edit'])->name('users.edit');
    Route::post('/users/{id}', [UserController::class,'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class,'destroy'])->name('users.destroy');

    //Products
    Route::get('/products', [ProductController::class,'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class,'store'])->name('products.store');
    Route::get('/products/{id}/edit', [ProductController::class,'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class,'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class,'destroy'])->name('products.destroy');

    //Customers
    Route::get('/customers', [CustomerControllers::class,'index'])->name('customers.index');
    // Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    // Route::post('/products', [ProductController::class,'store'])->name('products.store');
    // Route::get('/products/{id}/edit', [ProductController::class,'edit'])->name('products.edit');
    // Route::put('/products/{id}', [ProductController::class,'update'])->name('products.update');
    // Route::delete('/products/{id}', [ProductController::class,'destroy'])->name('products.destroy');
});

require __DIR__.'/auth.php';
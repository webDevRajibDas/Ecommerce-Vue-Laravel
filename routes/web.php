<?php

use App\Http\Controllers\Admin\BrandController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\DashboardController;


Route::get('/', [HomeController::class, 'index'])->name('home');



// Customer panel routes (authenticated non-admin users)
Route::prefix('customer-panel')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () {
        return Inertia::render('customer-panel/Index');
    })->name('customer-panel');
});

// Public dashboard routes (authenticated users)
Route::prefix('dashboard')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});

// Admin-only dashboard routes
Route::prefix('dashboard')->middleware(['auth', 'verified', 'admin'])->group(function () {
    // Product routes (RESTful)
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/data', [ProductController::class, 'getProduct'])->name('products.data');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/bulk-delete', [ProductController::class, 'bulkDelete'])->name('products.bulk-delete');
    
    Route::get('/users', [UsersController::class, 'index'])->name('usersList');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categoriesList');

    // Inertia routes for categories
    Route::post('/categories', [CategoryController::class, 'store'])->name('categoriesStore');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categoriesUpdate');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categoryDestroy');
    //Route::delete('/categories', [CategoryController::class, 'destroyMultiple'])->name('categories.destroy.multiple');

    Route::get('/brands', [BrandController::class, 'index'])->name('brandsList');
    Route::post('/brands', [BrandController::class, 'store'])->name('brandsStore');
    Route::put('/brands/{brand}', [BrandController::class, 'update'])->name('brandsUpdate');
    Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('brandDestroy');
});



require __DIR__ . '/settings.php';

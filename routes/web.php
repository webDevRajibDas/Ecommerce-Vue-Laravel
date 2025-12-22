<?php
use App\Models\Podcast;
use Illuminate\Bus\Batch;
use App\Jobs\ProcessPodcast;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\DashboardController;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('dashboard')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::get('/products/create', [ProductController::class, 'create'])->name('productsCreate');
    Route::post('/products/store', [ProductController::class, 'store'])->name('productStore');
    Route::get('/users', [UsersController::class, 'index'])->name('usersList');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categoriesList');

// Inertia routes for categories

Route::post('/categories', [CategoryController::class, 'store'])->name('categoriesStore');
Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categoriesUpdate');
Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categoryDestroy');
//Route::delete('/categories', [CategoryController::class, 'destroyMultiple'])->name('categories.destroy.multiple');
    
});



require __DIR__.'/settings.php';

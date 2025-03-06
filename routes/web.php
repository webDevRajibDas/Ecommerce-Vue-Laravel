<?php


use App\Http\Controllers\HomepageController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;



Route::get('/', [HomepageController::class, 'homePage'])->name('homePage');
Route::get('/vendor-form', [HomepageController::class, 'vendorForm'])->name('vendor-form');
Route::post('/vendor-contact-form', [HomepageController::class, 'vendorContactForm'])->name('vendorContactForm');
Route::get('/category/{slug}', [HomepageController::class, 'showSubCatList'])->name('categories.show');
Route::get('/vendors/{slug}', [HomepageController::class, 'showVendorList'])->name('vendors.show');
Route::get('/product/{slug}', [HomepageController::class, 'productShowDetail'])->name('product.show');


Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

require base_path('routes/admin.php');

Route::get('/clear-cache', function() {
    Artisan::call('config:cache');
    Artisan::call('route:cache');
    Artisan::call('view:cache');
    Artisan::call('cache:clear');
    return 'Cache cleared!';
});

Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'storage link created';
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UsersController;

Route::prefix('users')->group(function () {
    Route::post('/', [UsersController::class, 'store']);
    Route::get('/{userId}', [UsersController::class, 'show']);
    Route::post('/{userId}/orders', [UsersController::class, 'createOrder']);
    Route::get('/{userId}/orders', [UsersController::class, 'getUserOrders']);
});
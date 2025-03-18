<?php


use Illuminate\Support\Facades\Route;

Route::prefix('vendor')->name('vendor.')->middleware(['auth', 'isAdmin'])->group(function () {


});

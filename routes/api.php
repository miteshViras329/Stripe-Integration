<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;

Route::group(['prefix' => '/products', 'controller' => ProductController::class], function () {
    Route::get('/', 'index');
    Route::get('{id}', 'show');
    Route::post('create', 'store');
});

Route::group(['prefix' => '/prices', 'controller' => PriceController::class], function () {
    Route::get('/', 'index');
    Route::get('{id}', 'show');
    Route::post('create', 'store');
});

Route::group(['prefix' => '/payments', 'controller' => PaymentController::class], function () {
    Route::post('create', 'store');
    Route::get('/{id}', 'show');
});

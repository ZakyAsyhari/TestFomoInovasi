<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FlashSaleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('customer')->group(function () {
    Route::get('/', [CustomerController::class, 'index']);
});

Route::prefix('flash-sale')->group(function () {
    Route::get('/', [FlashSaleController::class, 'index']);
    Route::post('/', [FlashSaleController::class, 'store']);
    Route::get('/{id}', [FlashSaleController::class, 'show']);
    Route::put('/{id}', [FlashSaleController::class, 'update']);
    Route::delete('/{id}', [FlashSaleController::class, 'destroy']);
});

Route::prefix('product')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/', [ProductController::class, 'store']);
    Route::get('/{id}', [ProductController::class, 'show']);
    Route::put('/{id}', [ProductController::class, 'update']);
    Route::delete('/{id}', [ProductController::class, 'destroy']);
});


Route::prefix('order')->group(function () {
    Route::post('/', [OrderController::class, 'store']);
    Route::get('/', [OrderController::class, 'index']);
    Route::get('/{id}', [OrderController::class, 'show']);
});

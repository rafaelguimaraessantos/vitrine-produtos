<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Product routes
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{productId}', [ProductController::class, 'show']);
    Route::post('/sync', [ProductController::class, 'sync']);
});

// Order routes
Route::prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::get('/{orderId}', [OrderController::class, 'show']);
    Route::post('/', [OrderController::class, 'store']);
    Route::post('/{orderId}/send-external', [OrderController::class, 'sendToExternalApi']);
}); 
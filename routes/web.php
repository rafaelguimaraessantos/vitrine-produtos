<?php

use App\Http\Controllers\VitrineController;
use Illuminate\Support\Facades\Route;

Route::get('/', [VitrineController::class, 'index'])->name('vitrine.index');
Route::get('/checkout', [VitrineController::class, 'checkout'])->name('vitrine.checkout');
Route::post('/cart/add', [VitrineController::class, 'addToCart'])->name('vitrine.add-to-cart');
Route::delete('/cart/remove/{productId}', [VitrineController::class, 'removeFromCart'])->name('vitrine.remove-from-cart');
Route::delete('/cart/clear', [VitrineController::class, 'clearCart'])->name('vitrine.clear-cart');
Route::post('/order/place', [VitrineController::class, 'placeOrder'])->name('vitrine.place-order');
Route::get('/order/{orderId}/success', [VitrineController::class, 'orderSuccess'])->name('vitrine.order.success');
Route::post('/order/{orderId}/send-external', [VitrineController::class, 'sendOrderToExternalApi'])->name('vitrine.send-order-external'); 
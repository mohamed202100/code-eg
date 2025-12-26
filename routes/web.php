<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

Route::resource('categories', CategoryController::class);

Route::resource('products', ProductController::class);

Route::resource('carts', CartController::class)->except('store');

Route::resource('cartItems', CartItemController::class)->only('destroy');

Route::patch('/cartItems/{cartItem}/increment', [CartItemController::class, 'increment'])
    ->name('cartItems.increment');

Route::patch('/cartItems/{cartItem}/decrement', [CartItemController::class, 'decrement'])
    ->name('cartItems.decrement');

Route::post('/cart/store/{product}', [CartController::class, 'store'])
    ->name('carts.store');



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

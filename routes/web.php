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


Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');


Route::middleware('permission:create categories')->group(function () {
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
});

Route::middleware('permission:edit categories')->group(function () {
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
});

Route::middleware('permission:delete categories')->group(function () {
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});


Route::get('/products', [ProductController::class, 'index'])
    ->name('products.index');

Route::get('/products/{product}', [ProductController::class, 'show'])
    ->name('products.show');

Route::middleware('permission:create products')->group(function () {
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
});

Route::middleware('permission:edit products')->group(function () {
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
});


Route::delete('/products/{product}', [ProductController::class, 'destroy'])
    ->middleware('permission:delete products')
    ->name('products.destroy');



Route::resource('carts', CartController::class)->except('store');

Route::resource('cartItems', CartItemController::class)->only('destroy');

Route::patch('/cartItems/{cartItem}/increment', [CartItemController::class, 'increment'])
    ->name('cartItems.increment');

Route::patch('/cartItems/{cartItem}/decrement', [CartItemController::class, 'decrement'])
    ->name('cartItems.decrement');

Route::post('/cart/store/{product}', [CartController::class, 'store'])
    ->name('carts.store');



Route::resource('orders', OrderController::class);


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

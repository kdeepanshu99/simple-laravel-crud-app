<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');

// Route::post('/products', [ProductController::class, 'store'])->name('products.store');

// Route::get('/products/edit/{id}', [ProductController::class, 'edit'])->name('products.edit');

// Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');

// Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');


Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'index')->name('products.index');

    Route::get('/products/create', 'create')->name('products.create');

    Route::post('/products', 'store')->name('products.store');

    Route::get('/products/edit/{id}', 'edit')->name('products.edit');

    Route::put('/products/{id}', 'update')->name('products.update');

    Route::delete('/products/{id}', 'destroy')->name('products.destroy');
});

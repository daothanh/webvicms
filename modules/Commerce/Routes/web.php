<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('products')->group(function() {
    Route::get('/', 'ProductController@index')->name('commerce.product.index');
    Route::get('/category/{slug}', 'ProductController@category')->name('commerce.product.category');
    Route::get('/{slug}', 'ProductController@detail')->name('commerce.product.detail');
});

Route::prefix('cart')->group(function() {
    Route::get('/', 'CartController@index')->name('cart');
    Route::get('/book', 'CartController@book')->name('cart.book');
    Route::get('/book/complete', 'CartController@bookComplete')->name('cart.book.complete');
    Route::post('/add', 'CartController@addToCart')->name('cart.add');
});

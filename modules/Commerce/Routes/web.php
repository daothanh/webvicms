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

Route::prefix('commerce')->group(function() {
    Route::get('/', 'CommerceController@index')->name('commerce.product');
    Route::get('/category/{slug}', 'CommerceController@index')->name('commerce.category');
    Route::get('/{slug}', 'CommerceController@detail')->name('commerce.product.detail');
});

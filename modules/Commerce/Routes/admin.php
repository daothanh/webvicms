<?php
Route::group(['prefix' => 'commerce'], function () {
    Route::group(['prefix' => 'products'], function () {
        Route::get('/', ['uses' => 'ProductController@index', 'as' => 'admin.commerce.product.index'])->middleware(['permission:commerce.product.list products']);
        Route::get('/create', ['uses' => 'ProductController@create', 'as' => 'admin.commerce.product.create'])->middleware(['permission:commerce.product.create product']);
        Route::get('/edit/{id}', ['uses' => 'ProductController@edit', 'as' => 'admin.commerce.product.edit'])->where('id', '[0-9]+')->middleware(['permission:commerce.product.edit product']);
        Route::get('/duplicate/{id}', ['uses' => 'ProductController@duplicate', 'as' => 'admin.commerce.product.duplicate'])->where('id', '[0-9]+')->middleware(['permission:commerce.product.create product']);
        Route::post('/store', ['uses' => 'ProductController@store', 'as' => 'admin.commerce.product.store'])->middleware(['permission:commerce.product.create product|commerce.product.edit product']);
    });

    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', ['uses' => 'CategoryController@index', 'as' => 'admin.commerce.category.index'])->middleware(['permission:commerce.product_category.list product categories']);
        Route::get('/create', ['uses' => 'CategoryController@create', 'as' => 'admin.commerce.category.create'])->middleware(['permission:commerce.product_category.create product category']);
        Route::get('/edit/{id}', ['uses' => 'CategoryController@edit', 'as' => 'admin.commerce.category.edit'])->where('id', '[0-9]+')->middleware(['permission:commerce.product_category.edit product category']);
        Route::post('/store', ['uses' => 'CategoryController@store', 'as' => 'admin.commerce.category.store'])->middleware(['permission:commerce.product_category.create product category|commerce.product_category.edit product category']);
    });
});

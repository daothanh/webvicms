<?php
Route::group(['prefix' => 'commerce'], function () {
    Route::group(['prefix' => 'products'], function () {

        Route::bind('product', function ($id) {
            return app(\Modules\Commerce\Repositories\ProductRepository::class)->find($id);
        });

        Route::get('/', ['uses' => 'ProductController@index', 'as' => 'admin.commerce.product.index'])->middleware(['permission:list products']);
        Route::get('/create', ['uses' => 'ProductController@create', 'as' => 'admin.commerce.product.create'])->middleware(['permission:create product']);
        Route::get('/edit/{product}', ['uses' => 'ProductController@edit', 'as' => 'admin.commerce.product.edit'])->where('product', '[0-9]+')->middleware(['permission:edit product']);
        Route::get('/duplicate/{product}', ['uses' => 'ProductController@duplicate', 'as' => 'admin.commerce.product.duplicate'])->where('product', '[0-9]+')->middleware(['permission:create product']);
        Route::post('/store', ['uses' => 'ProductController@store', 'as' => 'admin.commerce.product.store'])->middleware(['permission:create product|edit product']);
    });

    Route::group(['prefix' => 'categories'], function () {

        Route::bind('category1', function ($id) {
            return app(\Modules\Commerce\Repositories\CategoryRepository::class)->find($id);
        });

        Route::get('/', ['uses' => 'CategoryController@index', 'as' => 'admin.commerce.category.index'])->middleware(['permission:list product categories']);
        Route::get('/create', ['uses' => 'CategoryController@create', 'as' => 'admin.commerce.category.create'])->middleware(['permission:create product category']);
        Route::get('/edit/{category1}', ['uses' => 'CategoryController@edit', 'as' => 'admin.commerce.category.edit'])->where('category1', '[0-9]+')->middleware(['permission:edit product category']);
        Route::post('/store', ['uses' => 'CategoryController@store', 'as' => 'admin.commerce.category.store'])->middleware(['permission:create product category|edit product category']);
    });
});

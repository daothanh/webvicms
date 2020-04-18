<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'commerce'], function () {
    Route::group(['prefix' => '/products', 'middleware' => ['api.auth']], function () {
        Route::get('index', [
            'as' => 'api.commerce.product.index',
            'uses' => 'ProductController@index',
        ])->middleware('api.permission:commerce.product.list products');
        Route::post('delete/multiple', [
            'as' => 'api.commerce.product.delete-multiple',
            'uses' => 'ProductController@destroyMultiple',
        ])->middleware('api.permission:commerce.product.delete product');
        Route::delete('delete/{id}', [
            'as' => 'api.commerce.product.delete',
            'uses' => 'ProductController@destroy',
        ])->where('id', '[0-9]+')
            ->middleware('api.permission:commerce.product.delete product');
        Route::post('force-delete/multiple', [
            'as' => 'api.commerce.product.force-delete-multiple',
            'uses' => 'ProductController@forceDestroyMultiple',
        ])->middleware('api.permission:commerce.product.delete product');
        Route::delete('force-delete/{productId}', [
            'as' => 'api.commerce.product.force-delete',
            'uses' => 'ProductController@forceDestroy',
        ])->where('productId', '[0-9]+')
            ->middleware('api.permission:commerce.product.delete product');

        Route::post('store', [
            'as' => 'api.commerce.product.store',
            'uses' => 'ProductController@store',
        ])->middleware('api.permission:commerce.product.create product|commerce.product.edit product');
        Route::post('restore/multiple', [
            'as' => 'api.commerce.product.restore-multiple',
            'uses' => 'ProductController@restoreMultiple',
        ])->middleware('api.permission:commerce.product.create product|commerce.product.edit product');
        Route::post('restore/{id}', [
            'as' => 'api.commerce.product.restore',
            'uses' => 'ProductController@restore',
        ])->where('id', '[0-9]+')
            ->middleware('api.permission:commerce.product.create product|commerce.product.edit product');
        Route::post('toggle-status', [
            'as' => 'api.commerce.product.toggle_status',
            'uses' => 'ProductController@toggleStatus',
        ])->middleware('api.permission:commerce.product.create product|commerce.product.edit product');
    });
    Route::group(['prefix' => '/categories', 'middleware' => ['api.auth']], function () {
        Route::get('index', [
            'as' => 'api.commerce.category.index',
            'uses' => 'CategoryController@index',
        ])->middleware('api.permission:commerce.product_category.list product categories');
        Route::post('delete/multiple', [
            'as' => 'api.commerce.category.delete-multiple',
            'uses' => 'CategoryController@destroyMultiple',
        ])->middleware('api.permission:commerce.product_category.delete product category');
        Route::delete('delete/{id}', [
            'as' => 'api.commerce.category.delete',
            'uses' => 'CategoryController@destroy',
        ])->where('id', '[0-9]+')
            ->middleware('api.permission:commerce.product_category.delete product category');
        Route::post('force-delete/multiple', [
            'as' => 'api.commerce.category.force-delete-multiple',
            'uses' => 'CategoryController@forceDestroyMultiple',
        ])->middleware('api.permission:commerce.product_category.delete product category');
        Route::delete('force-delete/{id}', [
            'as' => 'api.commerce.category.force-delete',
            'uses' => 'CategoryController@forceDestroy',
        ])->where('id', '[0-9]+')
            ->middleware('api.permission:commerce.product_category.delete product category');

        Route::post('store', [
            'as' => 'api.commerce.category.store',
            'uses' => 'CategoryController@store',
        ])
            ->middleware('api.permission:commerce.product_category.create product category|commerce.product_category.edit product category');
        Route::post('restore/multiple', [
            'as' => 'api.commerce.category.restore-multiple',
            'uses' => 'CategoryController@restoreMultiple',
        ])
            ->middleware('api.permission:commerce.product_category.create product category|commerce.product_category.edit product category');
        Route::post('restore/{id}', [
            'as' => 'api.commerce.category.restore',
            'uses' => 'CategoryController@restore',
        ])->where('id', '[0-9]+')
            ->middleware('api.permission:commerce.product_category.create product category|commerce.product_category.edit product category');
        Route::post('toggle-status', [
            'as' => 'api.commerce.category.toggle_status',
            'uses' => 'CategoryController@toggleStatus',
        ])
            ->middleware('api.permission:commerce.product_category.create product category|commerce.product_category.edit product category');
    });
});

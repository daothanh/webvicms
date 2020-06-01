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

Route::prefix('articles')->group(function() {
//    Route::get('/', 'BlogController@index')->name('blog.post');
    Route::get('/category/{slug}', 'BlogController@category')->name('blog.category')->where('slug', '[a-zA-Z0-9_-]+');
    Route::get('/{slug}', 'BlogController@detail')->name('blog.post.detail')->where('slug', '[a-zA-Z0-9_-]+');
});

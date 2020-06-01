<?php

Route::group(['prefix' => 'sliders'], function () {

    Route::bind('slider', function ($id) {
        return app(\Modules\Slider\Repositories\SliderRepository::class)->find($id);
    });

    Route::get('/', ['uses' => 'SliderController@index', 'as' => 'admin.slider.index'])->middleware(['permission:slider.list sliders']);
    Route::get('/create', ['uses' => 'SliderController@create', 'as' => 'admin.slider.create'])->middleware(['permission:slider.create slider']);
    Route::get('/edit/{slider}', ['uses' => 'SliderController@edit', 'as' => 'admin.slider.edit'])->where('slider', '[0-9]+')->middleware(['permission:slider.edit slider']);
    Route::post('/store', ['uses' => 'SliderController@store', 'as' => 'admin.slider.store'])->middleware(['permission:slider.create slider|slider.edit slider']);

    Route::group(['prefix' => '{slider}/items', 'middleware' => 'permission:slider.create slider|slider.edit slider'], function () {

        Route::bind('slide', function ($id) {
            return app(\Modules\Slider\Repositories\SliderItemRepository::class)->find($id);
        });

        Route::get('/', ['uses' => 'SliderItemController@index', 'as' => 'admin.slider.item.index']);
        Route::get('/create', ['uses' => 'SliderItemController@create', 'as' => 'admin.slider.item.create']);
        Route::get('/edit/{slide}', ['uses' => 'SliderItemController@edit', 'as' => 'admin.slider.item.edit'])->where('slide', '[0-9]+');
        Route::post('/store', ['uses' => 'SliderItemController@store', 'as' => 'admin.slider.item.store']);
    });
});

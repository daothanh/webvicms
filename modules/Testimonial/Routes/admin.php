<?php
Route::group(['prefix' => 'testimonials'], function () {

    Route::bind('testimonial', function ($id) {
        return app(\Modules\Testimonial\Repositories\TestimonialRepository::class)->find($id);
    });

    Route::get('/', ['uses' => 'TestimonialController@index', 'as' => 'admin.testimonial.index'])->middleware(['permission:testimonial.list testimonials']);
    Route::get('/trash', ['uses' => 'TestimonialController@trash', 'as' => 'admin.testimonial.trash'])->middleware(['permission:testimonial.list testimonials']);
    Route::get('/create', ['uses' => 'TestimonialController@create', 'as' => 'admin.testimonial.create'])->middleware(['permission:testimonial.create testimonial']);
    Route::get('/edit/{testimonial}', ['uses' => 'TestimonialController@edit', 'as' => 'admin.testimonial.edit'])->where('testimonial', '[0-9]+')->middleware(['permission:testimonial.edit testimonial']);
    Route::post('/store', ['uses' => 'TestimonialController@store', 'as' => 'admin.testimonial.store'])->middleware(['permission:testimonial.edit testimonial|testimonial.create testimonial']);
});

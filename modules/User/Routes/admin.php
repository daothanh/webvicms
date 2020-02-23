<?php

Route::group(['prefix' => 'users'], function () {
    Route::get('/', ['uses' => 'UserController@index', 'as' => 'admin.user.index'])->middleware(['permission:list users']);
    Route::get('/create', ['uses' => 'UserController@create', 'as' => 'admin.user.create'])->middleware(['permission:create user']);
    Route::get('/edit/{id}', ['uses' => 'UserController@edit', 'as' => 'admin.user.edit'])->where('id', '[0-9]+')->middleware(['permission:edit user']);
    Route::post('/store', ['uses' => 'UserController@store', 'as' => 'admin.user.store'])->middleware(['permission:create user|edit user']);
});

Route::group(['prefix' => 'roles'], function () {
    Route::get('/', ['uses' => 'RoleController@index', 'as' => 'admin.role.index'])->middleware(['permission:list roles']);
    Route::get('/create', ['uses' => 'RoleController@create', 'as' => 'admin.role.create'])->middleware(['permission:create role']);
    Route::get('/edit/{id}', ['uses' => 'RoleController@edit', 'as' => 'admin.role.edit'])->where('id', '[0-9]+')->middleware(['permission:edit role']);
    Route::post('/store', ['uses' => 'RoleController@store', 'as' => 'admin.role.store'])->middleware(['permission:create role|edit role']);
});

/*Route::group(['prefix' => 'permissions'], function () {
    Route::get('/', ['uses' => 'PermissionController@index', 'as' => 'admin.permission.index'])->middleware(['permission:list permissions']);
    Route::get('/create', ['uses' => 'PermissionController@create', 'as' => 'admin.permission.create'])->middleware(['permission:create permission']);
    Route::get('/edit/{id}', ['uses' => 'PermissionController@edit', 'as' => 'admin.permission.edit'])->where('id', '[0-9]+')->middleware(['permission:edit permission']);
    Route::post('/store', ['uses' => 'PermissionController@store', 'as' => 'admin.permission.store'])->middleware(['permission:create permission|edit permission']);
});*/

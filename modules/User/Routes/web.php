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

Route::prefix('user')->group(function () {
    Route::get('/', 'UserController@index');
});

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('login');
Route::get('admin_login', 'Auth\LoginController@showAdminLoginForm')->name('admin.login');
Route::post('admin_login', 'Auth\LoginController@adminLogin')->name('admin.login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('login/facebook', 'Auth\LoginController@facebook')->name('login.facebook');
Route::get('login/facebook/callback', 'Auth\LoginController@facebookCallback')->name('login.facebook.callback');
Route::get('login/google', 'Auth\LoginController@google')->name('login.google');
Route::get('login/google/callback', 'Auth\LoginController@googleCallback')->name('login.google.callback');

// Registration Routes...
if (config('user.account.register')) {
    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::get('register/success', 'Auth\RegisterController@registerSuccess')->name('register.success');
    Route::post('register', 'Auth\RegisterController@register');
}

// Password Reset Routes...
if (config('user.account.reset')) {
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
}
// Email Verification Routes...
if (config('user.account.verify')) {
    Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
    Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::get('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
}

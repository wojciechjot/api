<?php

use Illuminate\Support\Facades\Route;

Route::post('/register', 'Auth\RegisterController@register')
    ->name('register')
;

Route::post('/enable-user/{token}', 'Auth\RegisterController@enable')
    ->name('enable-user')
;

Route::post('/remind-password', 'Auth\PasswordController@remindPassword')
    ->name('remind-password')
;

Route::post('/set-password', 'Auth\PasswordController@setPassword')
    ->name('set-password')
;

Route::resource('posts', 'PostController')
    ->only(['show', 'store', 'update', 'destroy'])
    ->middleware('auth:api')
;

Route::resource('posts', 'PostController')
    ->only(['index'])
;

Route::resource('post-images', 'PostImageController')
    ->only(['show', 'store', 'update', 'destroy'])
    ->middleware('auth:api')
;

Route::resource('post-images', 'PostImageController')
    ->only(['index'])
;

Route::get('/users/{id}/posts', 'UserController@indexPostsForUser')
    ->name('user.posts.index')
    ->middleware('auth:api')
;

Route::get('/users/me', 'UserController@me')
    ->name('user.me')
    ->middleware('auth:api')
;

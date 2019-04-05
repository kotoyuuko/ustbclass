<?php

Route::get('/', 'PagesController@root')->name('root')->middleware('verified');
Route::resource('users', 'UsersController', ['only' => ['update', 'edit']]);
Auth::routes(['verify' => true]);

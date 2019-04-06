<?php

Route::get('/', 'PagesController@root')->name('root')->middleware('verified');
Route::get('help', 'PagesController@help')->name('help');
Route::resource('users', 'UsersController', ['only' => ['update', 'edit']]);
Auth::routes(['verify' => true]);

<?php

Route::get('/', 'PagesController@root')->name('root')->middleware('auth');
Route::get('courses/{week?}', 'PagesController@courses')->name('courses')->middleware('verified');
Route::get('help', 'PagesController@help')->name('help');
Route::get('calendar/{token}/{user}-{week}.ics', 'PagesController@calendar')->name('calendar');
Route::resource('users', 'UsersController', ['only' => ['update', 'edit']]);
Auth::routes(['verify' => true]);

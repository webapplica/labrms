<?php

/*
|--------------------------------------------------------------------------
| page not found
|--------------------------------------------------------------------------
|
*/
Route::get('pagenotfound', 'HomeController@pagenotfound');

/*
|--------------------------------------------------------------------------
| Main Menu
|--------------------------------------------------------------------------
|
*/
Route::namespace('auth')->middleware(['session_start'])->group(function () {

	/*
	|--------------------------------------------------------------------------
	| homepage
	|--------------------------------------------------------------------------
	|
	*/
	// Route::get('/','HomeController@index');
	Route::get('/','SessionsController@create');

	/*
	|--------------------------------------------------------------------------
	| login
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('login', 'SessionsController@create');
	Route::post('login', 'SessionsController@store');

	/*
	|--------------------------------------------------------------------------
	| reset
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('reset', 'SessionsController@getResetForm');
	Route::post('reset', 'SessionsController@reset');
});


/*
|--------------------------------------------------------------------------
| authenticated users
|--------------------------------------------------------------------------
|
*/
Route::namespace('auth')->middleware(['auth'])->group(function(){
	Route::get('profile', 'SessionsController@show');
	Route::get('settings', 'SessionsController@edit');
	Route::post('settings', 'SessionsController@update');
	Route::get('logout','SessionsController@destroy');

});


/*
|--------------------------------------------------------------------------
| authenticated users
|--------------------------------------------------------------------------
|
*/
Route::middleware(['auth'])->group(function(){

	Route::get('dashboard','DashboardController@index');

});
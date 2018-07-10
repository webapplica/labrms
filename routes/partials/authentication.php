<?php

/*
|--------------------------------------------------------------------------
| page not found
|--------------------------------------------------------------------------
|
*/
Route::get('pagenotfound',['as'=>'pagenotfound','uses'=>'HomeController@pagenotfound']);

/*
|--------------------------------------------------------------------------
| Main Menu
|--------------------------------------------------------------------------
|
*/
Route::middleware(['session_start'])->group(function () {

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
	Route::get('login', ['as'=>'login.index','uses'=>'SessionsController@create']);
	Route::post('login', ['as'=>'login.store','uses'=>'SessionsController@store']);

	/*
	|--------------------------------------------------------------------------
	| reset
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('reset',['as'=>'reset','uses'=>'SessionsController@getResetForm']);
	Route::post('reset',['as'=>'reset.store','uses'=>'SessionsController@reset']);
});


/*
|--------------------------------------------------------------------------
| authenticated users
|--------------------------------------------------------------------------
|
*/
Route::middleware(['auth'])->group(function(){

	Route::resource('dashboard','DashboardController', array('only'=>array('index')));

	Route::get('profile',['as'=>'profile.index','uses'=>'SessionsController@show']);

	Route::get('settings',['as'=>'settings.edit','uses'=>'SessionsController@edit']);

	Route::post('settings',['as'=>'settings.update','uses'=>'SessionsController@update']);

	Route::get('logout','SessionsController@destroy');

});
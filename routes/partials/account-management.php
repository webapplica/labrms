<?php

Route::middleware(['auth','laboratorystaff'])->group(function(){

	Route::resource('account','AccountsController');
	Route::put('account/access/update',[
			'as' => 'account.accesslevel.update',
			'uses' => 'AccountsController@changeAccessLevel'
	]);

	Route::post('account/password/reset','AccountsController@resetPassword');
	Route::post('account/activate/{id}','AccountsController@activateAccount');

});

<?php

Route::middleware(['auth','laboratorystaff'])->group(function(){

	Route::resource('account','AccountsController');
	Route::get('account/view/deleted',[
			'as'=>'account.retrieveDeleted',
			'uses'=>'AccountsController@retrieveDeleted'
	]);

	Route::delete('account/view/deleted/{id}',[
			'as'=>'account.restore',
			'uses'=>'AccountsController@restore'
	]);

	Route::put('account/access/update',[
			'as' => 'account.accesslevel.update',
			'uses' => 'AccountsController@changeAccessLevel'
	]);

	Route::post('account/password/reset','AccountsController@resetPassword');
	Route::post('account/activate/{id}','AccountsController@activateAccount');

});

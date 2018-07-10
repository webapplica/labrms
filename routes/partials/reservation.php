<?php

/** 
 * List of all the routes for reservation
*/

Route::middleware(['auth'])->group(function(){
    Route::resource('dashboard', 'DashboardController', array('only'=>array('index')));

    Route::prefix('reservation')->group(function () {
        Route::get('create', [
            'as' => 'reservation.create',
            'uses' => 'ReservationController@create'
        ]);

        Route::get('{id}', [
            'as' => 'reservation.show',
            'uses' => 'ReservationController@show'
        ]);

        Route::post('/', [
            'as' => 'reservation.store',
            'uses' => 'ReservationController@store'
        ]);
    });


	Route::prefix('reservation')->group(function(){

		Route::post('claim',[
			'as' => 'reservation.claim',
			'uses' => 'ReservationController@claim'
		]);

		Route::get('/',[
			'as' => 'reservation.index',
			'uses' => 'ReservationController@index'
		]);

		Route::post('{reservation}/approve',[
			'as' => 'reservation.approve',
			'uses' => 'ReservationController@approve'
		]);

		Route::post('{reservation}/disapprove',[
			'as' => 'reservation.disapprove',
			'uses' => 'ReservationController@disapprove'
		]);

		/*
		|--------------------------------------------------------------------------
		| Items for reservation
		|--------------------------------------------------------------------------
		|
		*/
		Route::prefix('items')->group(function(){
			Route::get('list',[
				'as' => 'reservation.items.list.index',
				'uses' => 'ReservationItemsController@index'
			]);
	
			Route::post('list',[
				'uses' => 'ReservationItemsController@update'
			]);
	
		});
	});

});
<?php

/** 
 * List of all the routes for ticketing
*/

/*
|--------------------------------------------------------------------------
| Accessible urls student and faculty only
|--------------------------------------------------------------------------
|
*/
Route::middleware(['auth'])->group(function(){

	/*
	|--------------------------------------------------------------------------
	| Ticket list
	|--------------------------------------------------------------------------
	|
	*/
	Route::resource('ticket','TicketsController',[
		'except'=>array('show')
    ]);
    
	/*
	|--------------------------------------------------------------------------
	| Ticket Resolve
	|--------------------------------------------------------------------------
	|
	*/
	Route::post('ticket/resolve',[
		'as' => 'ticket.resolve',
		'uses' => 'TicketsController@resolve'
	]);


	/*
	|--------------------------------------------------------------------------
	| ticket per workstation
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('ticket/workstation/{id}','TicketsController@getPcTicket');

	/*
	|--------------------------------------------------------------------------
	| ticket per room
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('ticket/room/{id}','RoomsController@getRoomTickets');

	/*
	|--------------------------------------------------------------------------
	| transfer ticket
	|--------------------------------------------------------------------------
	|
	*/
	Route::post('ticket/transfer/{id}',[
		'as' => 'ticket.transfer',
		'uses' => 'TicketsController@transfer'
	]);


	/*
	|------------------c--------------------------------------------------------
	| maintenance ticket
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('ticket/maintenance',[
		'uses'=>'MaintenanceTicketsController@index'
	]);

	/*
	|--------------------------------------------------------------------------
	| maintenance ticket function
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('ticket/maintenance/create',[
		'as'=>'ticket.maintenance.create',
		'uses'=>'MaintenanceTicketsController@create'
	]);

	/*
	|--------------------------------------------------------------------------
	| maintenance ticket function
	|--------------------------------------------------------------------------
	|
	*/
	Route::post('ticket/maintenance',[
		'as'=>'ticket.maintenance',
		'uses'=>'TicketsController@maintenance'
	]);

	/*
	|--------------------------------------------------------------------------
	| ticket history
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('ticket/maintenance/{id}',[
		'as' => 'ticket.maintenance.view',
		'uses' => 'MaintenanceTicketsController@show'
	]);

	/*
	|--------------------------------------------------------------------------
	| Reopen Ticket
	|--------------------------------------------------------------------------
	|
	*/
	Route::post('ticket/{id}/reopen','TicketsController@reOpenTicket');

	/*
	|--------------------------------------------------------------------------
	| ticket history
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('ticket/{id}',[
		'as' => 'ticket.history.view',
		'uses' => 'TicketsController@show'
	]);

});
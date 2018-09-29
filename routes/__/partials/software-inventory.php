<?php


Route::middleware(['auth','laboratorystaff'])->group(function () {


	/*
	|--------------------------------------------------------------------------
	| Reports
	|--------------------------------------------------------------------------
	|
	*/
	Route::prefix('reports')->group(function(){
		Route::get("/",[
			'as' => 'reports.index',
			'uses' => 'ReportsController@index'
		]);

		Route::get("{report}",[
			'as' => 'reports.generate',
			'uses' => 'ReportsController@generate'
		]);
	});

	/*
	|--------------------------------------------------------------------------
	| Reports
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('report','ReportsController@index');

	/*
	|--------------------------------------------------------------------------
	| assign software to a room
	|--------------------------------------------------------------------------
	|
	*/
	Route::post('software/room/assign',[
		'as' => 'software.room.assign',
		'uses' => 'SoftwareController@assignSoftwareToRoom'
	]);

	/*
	|--------------------------------------------------------------------------
	| remove software from a room
	|--------------------------------------------------------------------------
	|
	*/
	Route::post('software/room/remove/{id}/{room}',[
		'as' => 'software.room.remove',
		'uses' => 'SoftwareController@removeSoftwareFromRoom'
	]);
});


/*
|--------------------------------------------------------------------------
| Ajax Request made by all user only
|--------------------------------------------------------------------------
|
*/

Route::middleware(['auth','laboratorystaff'])->group(function () {

	/*
	|--------------------------------------------------------------------------
	| get item information
	| returns pc info if linked to pc
	| returns item information if not
	|--------------------------------------------------------------------------
	|
	*/
	Route::get("get/item/information/{propertynumber}",[
		'as' => 'item.information',
		'uses' => 'ItemsController@getItemInformation'
	]);

	/*
	|--------------------------------------------------------------------------
	| return all item types
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/item/type/all',[
		'as' => 'item.type.all',
		'uses'=>'ItemTypesController@getAllItemTypes'
	]);

	/*
	|--------------------------------------------------------------------------
	| return all equipment item type
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/inventory/item/type/equipment',[
		'as' => 'inventory.type.equipment',
		'uses'=>'ItemTypesController@getItemTypesForEquipmentInventory'
	]);

	/*
	|--------------------------------------------------------------------------
	| return all supply
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/inventory/item/type/supply',[
		'as' => 'inventory.type.supply',
		'uses'=>'ItemTypesController@getItemTypesForSuppliesInventory'
	]);

	/*
	|--------------------------------------------------------------------------
	| returns a list of A.R. based on 'id' given
	| used in Select Box -> Item Profile
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/item/profile/receipt/all',[
		'as'=>'item.profile.receipt.all',
		'uses'=>'ItemsController@getAllReceipt'
	]);

	/*
	|--------------------------------------------------------------------------
	| get all license types for the software
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/software/license/all',[
		'as'=>'software.license.all',
		'uses'=>'SoftwareController@getAllLicenseTypes'
	]);

	/*
	|--------------------------------------------------------------------------
	| get all license for the software
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/software/{id}/license/all',[
		'uses'=>'SoftwareLicenseController@getSoftwareLicense'
	]);

	/*
	|--------------------------------------------------------------------------
	| return all brands
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/item/brand/all',[
		'as' => 'inventory.brand.all',
		'uses' => 'ItemsController@getItemBrands'
	]);

	/*
	|--------------------------------------------------------------------------
	| return all models
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/item/model/all',[
		'as' => 'inventory.model.all',
		'uses' => 'ItemsController@getItemModels'
	]);

	/*
	|--------------------------------------------------------------------------
	| return all property number
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/propertynumber/all',[
		'as' => 'item.profile.propertynumber.all',
		'uses' => 'ItemsController@getAllPropertyNumber'
	]);

	/*
	|--------------------------------------------------------------------------
	| return all property number on server
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/item/propertynumber/server',[
		'as' => 'inventory.propertynumber.server',
		'uses' => 'ItemsController@getPropertyNumberOnServer'
	]);

	/*
	|--------------------------------------------------------------------------
	| return all unassigned system unit
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/item/profile/systemunit/unassigned',[
		'as' => 'item.profile.systemunit.unassigned',
		'uses' => 'ItemsController@getUnassignedSystemUnit'
	]);


	/*
	|--------------------------------------------------------------------------
	| return all item brands on inventory
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/inventory/item/brand','ItemInventoryController@getBrands');

	/*
	|--------------------------------------------------------------------------
	| return all models on inventory
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/inventory/item/model','ItemInventoryController@getModels');


	/*
	|--------------------------------------------------------------------------
	| reutrn all system unit property number
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/item/profile/systemunit/propertynumber','ItemsController@getSystemUnitList');


	/*
	|--------------------------------------------------------------------------
	| return all monitor property number
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/item/profile/monitor/propertynumber','ItemsController@getMonitorList');

	/*
	|--------------------------------------------------------------------------
	| return all monitor property number
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/item/profile/mouse/propertynumber','ItemsController@getMouseList');


	/*
	|--------------------------------------------------------------------------
	| return all avr property number
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/item/profile/avr/propertynumber','ItemsController@getAVRList');


	/*
	|--------------------------------------------------------------------------
	| return all keyboard property number
	|--------------------------------------------------------------------------
	|
	*/
	Route::get("get/item/profile/keyboard/propertynumber",'ItemsController@getKeyboardList');

	/*
	|--------------------------------------------------------------------------
	| return all supply brands
	|--------------------------------------------------------------------------
	|
	*/
	Route::get("get/supply/brand",'SuppliesController@getBrandList');

	/*
	|--------------------------------------------------------------------------
	| return supply item type base on brand
	|--------------------------------------------------------------------------
	|
	*/
	Route::get("get/supply/{itemtype}/{brand}","SuppliesController@getSupplyInformation");

	/*
	|--------------------------------------------------------------------------
	| return unassigned monitor
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/item/profile/monitor/unassigned',[
		'as' => 'item.profile.monitor.unassigned',
		'uses' => 'ItemsController@getUnassignedMonitor'
	]);

	/*
	|--------------------------------------------------------------------------
	| return unassigned avr
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/item/profile/avr/unassigned',[
		'as' => 'item.profile.avr.unassigned',
		'uses' => 'ItemsController@getUnassignedAVR'
	]);

	/*
	|--------------------------------------------------------------------------
	| return unassigned keyboard
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/item/profile/keyboard/unassigned',[
		'as' => 'item.profile.keyboard.unassigned',
		'uses' => 'ItemsController@getUnassignedKeyboard'
	]);

	/*
	|--------------------------------------------------------------------------
	| check if existing
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/{itemtype}/{brand}/{model}',[
		'as' => 'item.profile.checkifexisting',
		'uses' => 'ItemsController@checkifexisting'
	]);

	/*
	|--------------------------------------------------------------------------
	| get all maintenance activities
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/maintenance/activity/all',[
		'as' => 'maintenance.activity.all',
		'uses' => 'MaintenanceActivityController@getAllEquipmentSupport'
	]);

	/*
	|--------------------------------------------------------------------------
	| get maintenance activities
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/maintenance/activity',[
		'as' => 'maintenance.activity',
		'uses' => 'MaintenanceActivityController@getMaintenanceActivity'
	]);

	/*
	|--------------------------------------------------------------------------
	| get all ticket types
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/ticket/type/all',[
		'as' => 'ticket.type.all',
		'uses' => 'TicketTypeController@getAllTicketTypes'
	]);

	/*
	|--------------------------------------------------------------------------
	| get ticket history
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/ticket/history/{id}',[
		'as' => 'ticket.history',
		'uses' => 'TicketsController@showHistory'
	]);

	/*
	|--------------------------------------------------------------------------
	| get all preventive maintenance
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/maintenance/activity/preventive',[
		'as' => 'ticket.type.preventive',
		'uses' => 'MaintenanceActivityController@getPreventiveEquipmentSupport'
	]);

	/*
	|--------------------------------------------------------------------------
	| get all corrective maintenance action
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/maintenance/activity/corrective',[
		'as' => 'ticket.type.corrective',
		'uses' => 'MaintenanceActivityController@getCorrectiveEquipmentSupport'
	]);

	/*
	|--------------------------------------------------------------------------
	| get room inventory details
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/room/inventory/details/{id}',[
		'as' => 'room.inventory.profile',
		'uses' => 'RoomInventoryProfileController@getItemsAssigned'
	]);

	/*
	|--------------------------------------------------------------------------
	| get room name from id
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/room/name/{id}',[
		'as' => 'room.name',
		'uses' => 'RoomsController@getRoomName'

	]);

	/*
	|--------------------------------------------------------------------------
	| get all software installed on a workstation
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/software/installed/{id}',[
		'as' => 'workstation.pc.software',
		'uses' => 'WorkstationSoftwareController@getSoftwareInstalled'
	]);
	/*
	|--------------------------------------------------------------------------
	| get all reservation items brand
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/reservation/item/brand/all',[
		'as' => 'reservation.item.brand.all',
		'uses' => 'ReservationItemsController@getAllReservationItemBrand'
	]);

	/*
	|--------------------------------------------------------------------------
	| get all reservation items model
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/reservation/item/model/all',[
		'as' => 'reservation.item.model.all',
		'uses' => 'ReservationItemsController@getAllReservationItemModel'
	]);

	/*
	|--------------------------------------------------------------------------
	| get all property number of an item
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/reservation/item/propertynumber/all',[
		'as' => 'reservation.item.propertynumber.all',
		'uses' => 'ReservationItemsController@getAllReservationItemPropertyNumber'
	]);

	/*
	|--------------------------------------------------------------------------
	| get all software names
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/software/all/name',[
		'as' => 'software.all.name',
		'uses' => 'SoftwareController@getAllSoftwareName'
	]);

	/*
	|--------------------------------------------------------------------------
	| get all software license key
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/software/license/{id}/key',[
		'as' => 'software.license.all.key',
		'uses' => 'SoftwareLicenseController@getAllSoftwareLicenseKey'
	]);

	/*
	|--------------------------------------------------------------------------
	| get all accounts
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/account/all',[
			'as' => 'account.all',
			'uses' => 'AccountsController@getAllUsers'
	]);

	/*
	|--------------------------------------------------------------------------
	| get all laboratory staff
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/account/laboratory/staff/all',[
			'as' => 'account.laboratory.staff.all',
			'uses' => 'AccountsController@getAllLaboratoryUsers'
	]);

	/*
	|--------------------------------------------------------------------------
	| get status of certain property number
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/{propertynumber}/status',[
		'as' => 'item.information.status',
		'uses' => 'ItemsController@getStatus'
	]);

	/*
	|--------------------------------------------------------------------------
	| get tag information for ticket
	|--------------------------------------------------------------------------
	|
	*/
	Route::get('get/ticket/tag',[
		'uses' => 'TicketsController@getTagInformation'
	]);

});

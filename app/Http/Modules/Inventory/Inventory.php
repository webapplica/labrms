<?php

namespace App\Http\Modules\Inventory;

use Illuminate\Support\Facades\Route;

class Inventory
{

    /**
     * List all the routes in the ticketing system
     *
     * @return void
     */
    public static function routes()
    {
        Route::namespace('inventory')->middleware(['auth', 'role.staff'])->group(function () {

            Route::namespace('item')->group(function() {

                Route::get('inventory/{id}/profile', 'ProfileController@create');
                Route::post('inventory/{id}/profile', 'ProfileController@store');

                Route::get('inventory/{id}/release', 'ReleaseController@create');
                Route::post('inventory/{id}/release', 'ReleaseController@store');

                Route::get('inventory/{id}/receive', 'ReceiveController@create');
                Route::post('inventory/{id}/receive', 'ReceiveController@store');
            });

            Route::get('inventory','InventoryController@index');
            Route::get('inventory/create', 'InventoryController@create');
            Route::post('inventory', 'InventoryController@store');
            Route::get('inventory/{id}', 'InventoryController@show');

            Route::get('inventory/{id}/log', 'LogController@index');
            
            // Route::resource('item/profile','ItemsController');
            // Route::prefix('inventory')->group(function(){
            //     Route::get('/search',[
            //         'as' => 'inventory.search.view',
            //         'uses' => 'ItemInventoryController@searchView'
            //     ]);
        
            //     Route::post('/search',[
            //         'as' => 'inventory.search',
            //         'uses' => 'ItemInventoryController@search'
            //     ]);
        
            //     Route::post('release', 'ItemInventoryController@release');
            //     Route::get('{inventory}/log', 'ItemInventoryController@showLogs');
            //     Route::get('room/assign',[
            //         'as' => 'inventory.room.assign',
            //         'uses' => 'RoomInventoryAssignmentController@index'
            //     ]);
        
            //     Route::get('room',[
            //         'as' => 'inventory.room.index',
            //         'uses' => 'RoomInventoryController@index'
            //     ]);
            //     Route::post('room',[
            //         'as' => 'inventory.room.store',
            //         'uses' => 'RoomInventoryController@store'
            //     ]);
            //     Route::get('room/{id}',[
            //         'uses' => 'RoomInventoryController@show'
            //     ]);
            // });
        
            // Route::prefix('item/profile')->group(function(){
        
            //     Route::post('assign',[
            //         'as' => 'item.profile.assign',
            //         'uses' => 'ItemsController@assign'
            //     ]);
        
            //     Route::get('history/{id}','ItemsController@history');
            // });
        });
    }
}
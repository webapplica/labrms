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

                Route::resource('item', 'ItemController');

                Route::get('inventory/{id}/profile', 'ProfileController@create');
                Route::post('inventory/{id}/profile', 'ProfileController@store');

                Route::get('inventory/{id}/release', 'ReleaseController@create');
                Route::post('inventory/{id}/release', 'ReleaseController@store');

                Route::get('inventory/{id}/receive', 'ReceiveController@create');
                Route::post('inventory/{id}/receive', 'ReceiveController@store');

            });

            Route::namespace('profiling')->group(function() {
                Route::get('item/{id}/activity/add', 'ActivityController@create');
                Route::post('item/{id}/activity/add', 'ActivityController@store');
                
                Route::get('item/{id}/activity/condemn', 'CondemnController@create');
                Route::post('item/{id}/activity/condemn', 'CondemnController@store');
            });

            Route::get('inventory','InventoryController@index');
            Route::get('inventory/create', 'InventoryController@create');
            Route::post('inventory', 'InventoryController@store');
            Route::get('inventory/{id}', 'InventoryController@show');

            Route::get('inventory/{id}/log', 'LogController@index');
        });
    }
}
<?php

namespace App\Http\Modules\Ticket;

use Illuminate\Support\Facades\Route;

class Routes
{

    /**
     * List all the routes in the ticketing system
     *
     * @return void
     */
    public static function routes()
    {
        Route::middleware(['auth'])->namespace('ticketing')->group(function () {
            Route::resource('ticket','TicketController');
            
            Route::get('ticket/{id}/action', 'ActionController@create');
            Route::post('ticket/{id}/action', 'ActionController@store');

            Route::get('ticket/{id}/transfer', 'TransferController@create');
            Route::post('ticket/{id}/transfer', 'TransferController@store');

            Route::get('ticket/{id}/resolve', 'ResolutionController@create');
            Route::post('ticket/{id}/resolve', 'ResolutionController@store');

            Route::get('ticket/{id}/close', 'ClosureController@create');
            Route::post('ticket/{id}/close', 'ClosureController@store');
            
            Route::get('ticket/{id}/reopen', 'ReopenController@create');
            Route::post('ticket/{id}/reopen', 'ReopenController@store');
        });
    }
}
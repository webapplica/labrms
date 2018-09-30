<?php

namespace App\Http\Modules\Ticket;

use Illuminate\Support\Facades\Route;

class Ticket
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

            // Route::get('ticket/{id}/transfer', 'TransferController');
            // Route::post('ticket/{id}/transfer', 'TransferController');

            Route::get('ticket/{id}/resolve', 'ResolutionController@create');
            Route::post('ticket/{id}/resolve', 'ResolutionController@store');

            Route::get('ticket/{id}/close', 'ClosureController@create');
            Route::post('ticket/{id}/close', 'ClosureController@store');
        });
    }
}
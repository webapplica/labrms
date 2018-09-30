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
            // Route::get('ticket/workstation/{id}','Workstation');
            // Route::get('ticket/room/{id}','RoomsController@getRoomTickets');
            Route::post('ticket/{id}/transfer','TicketController@transfer');
            Route::post('ticket/{id}/reopen','TicketController@reopen');
        });
    }
}
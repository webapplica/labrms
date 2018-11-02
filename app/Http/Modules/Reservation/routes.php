<?php

namespace App\Http\Modules\Reservation;

use Illuminate\Support\Facades\Route; 

class Routes
{

    public static function all() 
    {
        Route::middleware(['auth'])->namespace('reservation')->group(function() {

            Route::prefix('reservation')->group(function () {

                Route::get('/', 'ReservationController@index');
                Route::post('/', 'ReservationController@store');

                Route::get('create', 'ReservationController@create');
                Route::get('{id}', 'ReservationController@show');

            //     Route::post('claim',[
            //         'as' => 'reservation.claim',
            //         'uses' => 'ReservationController@claim'
            //     ]);

            //     Route::post('{reservation}/approve',[
            //         'as' => 'reservation.approve',
            //         'uses' => 'ReservationController@approve'
            //     ]);

            //     Route::post('{reservation}/disapprove',[
            //         'as' => 'reservation.disapprove',
            //         'uses' => 'ReservationController@disapprove'
            //     ]);

            });
        });
    }
}
<?php

namespace App\Http\Modules\Reservation;

use Illuminate\Support\Facades\Route; 

class Routes
{

    public static function all() 
    {
        Route::middleware(['auth'])->namespace('reservation')->group(function() {

            Route::prefix('reservation')->group(function () {

                Route::get('/', 'ListController@index');
                Route::post('/', 'ListController@store');

                Route::get('create', 'ListController@create');
                Route::get('{id}', 'ListController@show');

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
                
            //     Route::prefix('items')->group(function() {
            //         Route::get('list',[
            //             'as' => 'reservation.items.list.index',
            //             'uses' => 'ReservationItemsController@index'
            //         ]);
            
            //         Route::post('list',[
            //             'uses' => 'ReservationItemsController@update'
            //         ]);
            
            //     });

            });
        });
    }
}
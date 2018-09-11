<?php

namespace App\Http\Modules\Reservation;

use Route; 

class Routes
{

    public static function all() 
    {
        Route::middleware(['auth'])->group(function() {
            Route::prefix('reservation')->group(function () {
                Route::get('/', 'ReservationController@index');
                Route::get('create', 'ReservationController@create');
                Route::get('{id}', 'ReservationController@show');
                Route::post('/', 'ReservationController@store');

                Route::post('claim',[
                    'as' => 'reservation.claim',
                    'uses' => 'ReservationController@claim'
                ]);

                Route::post('{reservation}/approve',[
                    'as' => 'reservation.approve',
                    'uses' => 'ReservationController@approve'
                ]);

                Route::post('{reservation}/disapprove',[
                    'as' => 'reservation.disapprove',
                    'uses' => 'ReservationController@disapprove'
                ]);
                
                Route::prefix('items')->group(function() {
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
    }
}
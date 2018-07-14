<?php

namespace App\Http\Modules\Reservation\Routes;

use Route;

class ReservationRoutes
{
    protected static $namespace;

    function __construct()
    {
    }

    public static function all() 
    {
        Route::prefix('reservation')->group(function () {
            Route::get('create', 'ReservationController@create');
            Route::get('{id}', 'ReservationController@show');
            Route::post('/', 'ReservationController@store');
        });
    }
}
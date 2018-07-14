<?php

namespace App\Http\Modules\Reservation; 

use Route;

class Reservation
{
    public static function routes()
    {
        \App\Http\Modules\Reservation\Routes\ReservationRoutes::all();
    }
}
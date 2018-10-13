<?php

namespace App\Http\Modules\Software; 

use Illuminate\Support\Facades\Route;

class Routes
{

    public static function all() 
    {

        Route::middleware(['auth', 'role.staff'])->namespace('maintenance\software')->group(function () {
            Route::post('software/{id}/license', 'LicenseController@store');
            Route::delete('software/{id}/license', 'LicenseController@remove');

            Route::post('software/{id}/assign/room', 'RoomAssignmentController@store');
            Route::delete('software/{id}/unassign/room/{room_id}', 'RoomAssignmentController@remove');
        });

    }
}
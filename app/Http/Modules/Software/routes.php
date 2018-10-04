<?php

namespace App\Http\Modules\Software; 

use Route;

class Routes
{

    public static function all() 
    {

        Route::middleware(['auth', 'role.staff'])->namespace('maintenance\software')->group(function () {
            Route::post('software/{id}/license', 'LicenseController@store');
            Route::delete('software/{id}/license', 'LicenseController@remove');
        });

    }
}
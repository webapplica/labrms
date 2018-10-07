<?php

namespace App\Http\Modules\Workstation;

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
        Route::middleware(['auth', 'role.staff'])->namespace('inventory/workstation')->group(function() {
            
            Route::post('workstation/{id}/deploy', 'AssignmentController@deploy');
            Route::post('workstation/{id}transfer', 'AssignmentController@transfer');
            
            /**
             * Routes for the list of softwares the workstation has
             */
            Route::namespace('software')->group(function() {
                Route::get('workstation/{id}/software', 'SoftwareController@index');
                Route::post('workstation/{id}/software', 'SoftwareController@store');

            });

            /**
             * Routes for the list of license the specific software of workstation has
             */
            Route::namespace('license')->group(function() {
                Route::get('workstation/{id}/software/{id}/license', 'LicenseController@index');
                Route::post('workstation/{id}/software/{id}/license', 'LicenseController@store');

            });

            Route::resource('workstation', 'WorkstationController');
        });
    }
}
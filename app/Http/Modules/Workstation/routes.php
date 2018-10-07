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
        Route::middleware(['auth', 'role.staff'])->namespace('workstation')->group(function() {
            
            Route::post('workstation/{id}/deploy', 'DeploymentController@deploy');
            Route::post('workstation/{id}transfer', 'TransferController@transfer');
            
            /**
             * Routes for the list of softwares the workstation has
             */
            Route::namespace('software')->group(function() {
                Route::get('workstation/{id}/software', 'AssignmentController@index');
                Route::post('workstation/{id}/software', 'AssignmentController@store');

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
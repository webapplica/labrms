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
        $middlewares = ['auth', 'role.staff'];
        $namespace = 'inventory\workstation';

        Route::middleware($middlewares)->namespace($namespace)->group(function() {
            
            // Route::post('workstation/{workstation}/deploy', 'AssignmentController@deploy');
            // Route::post('workstation/{workstation}transfer', 'AssignmentController@transfer');
            
            /**
             * Routes for the list of softwares the workstation has
             */
            Route::namespace('software')->group(function() {
                Route::get('workstation/{id}/software', 'SoftwareController@index');

                Route::get('workstation/{id}/software/install', 'InstallationController@create');
                Route::post('workstation/{id}/software/install', 'InstallationController@store');

                // Route::get('workstation/{workstation}/software/{software}', 'SoftwareController@show');

                Route::get('workstation/{workstation}/software/{software}/uninstall', 'UninstallController@showForm');
                Route::post('workstation/{workstation}/software/{software}/uninstall', 'UninstallController@uninstall');
            });

            /**
             * Routes for the list of license the specific software of workstation has
             */
            // Route::namespace('license')->group(function() {
            //     Route::get('workstation/{workstation}/software/{software}/license', 'LicenseController@index');
            //     Route::post('workstation/{workstation}/software/{software}/license', 'LicenseController@store');

            // });
            
            /**
             * Workstation actions routing methods
             */
            Route::prefix('workstation')->group(function() {
                Route::get('{id}/action/create', 'ActionController@get');
                Route::post('{id}/action', 'ActionController@store');

                Route::get('{id}/deploy', 'DeploymentController@get');
                Route::post('{id}/deploy', 'DeploymentController@store');

                Route::get('{id}/transfer', 'TransferController@get');
                Route::post('{id}/transfer', 'TransferController@store');

                Route::get('{id}/disassemble', 'DisassembleController@get');
                Route::post('{id}/disassemble', 'DisassembleController@store');
            });

            Route::resource('workstation', 'WorkstationController', [
                'except' => ['destroy']
            ]);
        });
    }
}
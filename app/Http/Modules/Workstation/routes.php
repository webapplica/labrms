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
        Route::middleware(['auth'])->group(function(){
            
            Route::post('workstation/deploy',[
                'as' => 'workstation.deploy',
                'uses' => 'WorkstationController@deploy'
            ]);
            
            Route::post('workstation/transfer',[
                'as' => 'workstation.transfer',
                'uses' => 'WorkstationController@transfer'
            ]);
        
            Route::get('workstation/{id}/softwares', 'WorkstationSoftwareController@getAllWorkstationSoftware');
            Route::resource('workstation', 'WorkstationController');
            Route::get('workstation/view/software','WorkstationSoftwareController@index');
            Route::get('workstation/software/{id}/assign','WorkstationSoftwareController@create');
            Route::get('workstation/software/{id}/remove','WorkstationSoftwareController@destroyView');
            Route::delete('workstation/software/{id}/remove',[
                'as' => 'workstation.software.destroy',
                'uses' => 'WorkstationSoftwareController@destroy'
            ]);
            
            Route::post('workstation/software/{id}/assign',[
                    'as' => 'workstation.software.assign',
                    'uses' => 'WorkstationSoftwareController@store'
            ]);
        
            Route::post('workstation/software/{id}/license/update',[
                    'as' => 'workstation.software.assign',
                    'uses' => 'WorkstationSoftwareController@update'
            ]);
        });
    }
}
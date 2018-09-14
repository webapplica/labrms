<?php

namespace App\Http\Modules\Account\Auth; 

use Route; 

class Routes
{

    /**
     * List all the routes under the account authentication 
     * modules
     * 
     * @return
     */
    public static function all() 
    {

        Route::namespace('auth')->middleware(['session_start'])->group(function () {
            Route::get('login', 'LoginController@form');
            Route::post('login', 'LoginController@login');

            Route::get('reset', 'PasswordResetController@form');
            Route::post('reset', 'PasswordResetController@reset');
        });

        Route::namespace('auth')->middleware(['auth'])->group(function() {
            Route::get('logout','LogoutController@logout');
            Route::post('logout','LogoutController@logout');
            
            Route::get('user/{user}', 'SessionsController@show');
            Route::get('settings', 'SessionsController@edit');
            Route::post('settings', 'SessionsController@update');

        });

    }
}
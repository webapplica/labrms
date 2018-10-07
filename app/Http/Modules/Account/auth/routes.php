<?php

namespace App\Http\Modules\Account\Auth; 

use Illuminate\Support\Facades\Route; 

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
        Route::namespace('auth')->group(function () {

            Route::middleware(['auth.before'])->group(function () {
                Route::get('login', 'LoginController@form');
                Route::post('login', 'LoginController@login');
    
                Route::get('reset', 'PasswordResetController@form');
                Route::post('reset', 'PasswordResetController@reset');
            });
    
            Route::middleware(['auth'])->group(function() {
                Route::get('user/{user}', 'SessionsController@show');
                Route::get('settings', 'SessionsController@edit');
                Route::post('settings', 'SessionsController@update');
            });
    
            Route::get('logout','LogoutController@logout');
            Route::post('logout','LogoutController@logout');
            
        });
    }
}
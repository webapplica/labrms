<?php

namespace App\Http\Modules\Account; 

use Illuminate\Support\Facades\Route;
use App\Http\Modules\Account\Auth\Routes as AuthenticationRoutes; 

class Routes
{

    public static function all() 
    {
        Route::middleware(['auth','lab.staff'])->namespace('maintenance')->group(function() {
            Route::resource('account','AccountController');
            Route::put('account/access/update', 'AccountController@changeAccessLevel');
            Route::post('account/password/reset','AccountController@resetPassword');
            Route::post('account/activate/{id}','AccountController@activateAccount');

        });

        AuthenticationRoutes::all();

    }
}
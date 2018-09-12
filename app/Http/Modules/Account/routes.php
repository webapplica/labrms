<?php

namespace App\Http\Modules\Account; 

use Route; 

class Routes
{

    public static function all() 
    {
        Route::middleware(['auth','lab.staff'])->group(function() {
            Route::resource('account','AccountsController');
            Route::put('account/access/update', 'AccountsController@changeAccessLevel');
            Route::post('account/password/reset','AccountsController@resetPassword');
            Route::post('account/activate/{id}','AccountsController@activateAccount');

        });

        Auth\Routes::all();

    }
}
<?php

use App\Http\Modules\Maintenance\Routes as Maintenance;
use App\Http\Modules\Reservation\Routes as Reservation;
use App\Http\Modules\Account\Routes as Account;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@dashboard');
Route::post('/', 'HomeController@dashboard');

Account::all();
Maintenance::all();
Reservation::all();

// require_once(base_path('routes/partials/inventory.php'));
// require_once(base_path('routes/partials/software-inventory.php'));
// require_once(base_path('routes/partials/ticketing.php'));
// require_once(base_path('routes/partials/workstation.php'));

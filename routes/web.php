<?php

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

require_once( base_path('routes/partials/account-management.php'));
require_once( base_path('routes/partials/authentication.php'));
require_once( base_path('routes/partials/inventory.php'));
App\Http\Modules\Maintenance\Maintenance::routes();
require_once( base_path('routes/partials/reservation.php'));
App\Http\Modules\Reservation\Reservation::routes();
require_once( base_path('routes/partials/software-inventory.php'));
require_once( base_path('routes/partials/ticketing.php'));
require_once( base_path('routes/partials/workstation.php'));

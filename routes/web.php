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

Route::get('/', 'HomeController@dashboard');
Route::post('/', 'HomeController@dashboard');

Route::get('dashboard', 'HomeController@dashboard');
Route::post('dashboard', 'HomeController@dashboard');

/*
|--------------------------------------------------------------------------
| Route Groups
|--------------------------------------------------------------------------
|
| This is the list of routes the system has. Each route is handled by a class
| under the package folder. You may look for the location of the package through
| the use statements above. Enjoy!
|
*/

App\Http\Modules\Account\Routes::all();
App\Http\Modules\Reservation\Routes::all();
App\Http\Modules\Maintenance\Routes::all();
App\Http\Modules\Software\Routes::all();
App\Http\Modules\Ticket\Routes::routes();
App\Http\Modules\Inventory\Routes::routes();
App\Http\Modules\Workstation\Routes::routes();

Route::get('report', 'ReportController@index');

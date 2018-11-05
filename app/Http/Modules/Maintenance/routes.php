<?php

namespace App\Http\Modules\Maintenance;

use Route;

class Routes
{
    public static function all()
    {

        Route::namespace('maintenance')->middleware(['auth', 'role.staff'])->group(function() {

            // Route::resource('academicyear','AcademicYearController');
            // Route::resource('event','SpecialEventController');
            Route::resource('faculty','FacultyController', ['except' => array('show')]);
            // Route::resource('inventory/software','SoftwareInventoryController');

            Route::namespace('item')->prefix('item')->group(function() {
                Route::resource('type','TypeController');
            });

            Route::resource('unit','UnitController', ['except' => array('show')] );

            Route::namespace('room')->group(function() {
                Route::resource('room/category','CategoryController');
                Route::resource('room','RoomController');
            });

            // Route::resource('lostandfound','LostAndFoundController');
            // Route::resource('maintenance/activity','MaintenanceActivityController');

            Route::namespace('reservation')->group(function() {
                Route::resource('purpose','PurposeController');
            });

            // Route::resource('schedule','LaboratoryScheduleController');
            // Route::resource('software/license','SoftwareLicenseController');
            Route::namespace('software')->group(function() {
                Route::resource('software/type','TypeController');
                Route::resource('software', 'SoftwareController');
            });

            // Route::resource('semester','SemesterController');
            // Route::resource('receipt', 'ReceiptsController');
            
            // Route::resource('room/log','RoomLogController');
            // Route::resource('room/scheduling','RoomSchedulingController');
            // Route::resource('lend','LentItemsController');
        });
    }
}
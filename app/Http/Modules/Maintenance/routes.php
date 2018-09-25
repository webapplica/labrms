<?php

namespace App\Http\Modules\Maintenance;

use Route;

class Routes
{
    public static function all()
    {

        Route::namespace('maintenance')->middleware(['auth', 'lab.staff'])->group(function () {

            Route::resource('academicyear','AcademicYearController');
            Route::resource('event','SpecialEventController');
            Route::resource('faculty','FacultiesController');
            Route::resource('inventory/software','SoftwareInventoryController');

            Route::namespace('item')->group(function() {
                Route::resource('item/type','TypeController');
            });

            Route::resource('lostandfound','LostAndFoundController');
            Route::resource('maintenance/activity','MaintenanceActivityController');
            Route::resource('purpose','PurposeController');
            Route::resource('schedule','LaboratoryScheduleController');
            Route::resource('software','SoftwareController',[ 'except' => array('show') ]);
            Route::resource('software/license','SoftwareLicenseController');
            Route::resource('software/type','SoftwareTypesController');
            Route::resource('semester','SemesterController');
            Route::resource('receipt', 'ReceiptsController');
            Route::resource('room','RoomsController');
            Route::resource('room/category','RoomCategoryController');
            Route::resource('room/log','RoomLogController');
            Route::resource('room/scheduling','RoomSchedulingController');
            Route::resource('lend','LentItemsController');
            Route::resource('unit','UnitController');
        });
    }
}
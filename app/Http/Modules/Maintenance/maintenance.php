<?php

namespace App\Http\Modules\Maintenance;

use Route;

class Maintenance
{
    public static function routes()
    {

        Route::namespace('maintenance')->middleware(['auth', 'laboratorystaff'])->group(function(){

            Route::resource('academicyear','AcademicYearController');
            Route::resource('event','SpecialEventController');
            Route::resource('faculty','FacultiesController');
            Route::resource('inventory/software','SoftwareInventoryController');
            Route::resource('item/type','ItemTypesController');
            Route::resource('lostandfound','LostAndFoundController');
            Route::resource('maintenance/activity','MaintenanceActivityController');
            Route::resource('purpose','PurposeController');
            Route::resource('schedule','LaboratoryScheduleController');
            Route::resource('software','SoftwareController',[
                    'except'=>array('show')
            ]);
            Route::resource('software/license','SoftwareLicenseController');
            Route::resource('software/type','SoftwareTypesController');
            Route::resource('semester','SemesterController');
            Route::resource('receipt', 'ReceiptsController');
            Route::resource('room','RoomsController');
            Route::resource('room/category','RoomCategoryController');
            Route::resource('room/log','RoomLogController');
            Route::resource('room/scheduling','RoomSchedulingController');
            Route::resource('lend','LentItemsController');
            Route::resource('unit','UnitsController');
        });
    }
}
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Company Header
    |--------------------------------------------------------------------------
    |
    | The value inputted in the field is equivalent to the clients basic information
    | To change the values, you need to update the environment variable for it to work
    */
    
    'header' => env('COMPANY_HEADER','Main Header'),

    /*
    |--------------------------------------------------------------------------
    | Company Sub Header
    |--------------------------------------------------------------------------
    |
    | The value inputted in the field is equivalent to the clients basic information
    | To change the values, you need to update the environment variable for it to work
    */

    'altheader' => env('COMPANY_ALTHEADER','Secondary Header'),

    /*
    |--------------------------------------------------------------------------
    | Company Address
    |--------------------------------------------------------------------------
    |
    | The value inputted in the field is equivalent to the clients basic information
    | To change the values, you need to update the environment variable for it to work
    */
    
    'address' => env('COMPANY_ADDRESS','Address'),

    /*
    |--------------------------------------------------------------------------
    | Company Department
    |--------------------------------------------------------------------------
    |
    | The value inputted in the field is equivalent to the clients basic information
    | To change the values, you need to update the environment variable for it to work
    */

    'department' => env('COMPANY_DEPARTMENT','Main Department'), 

    /*
    |--------------------------------------------------------------------------
    | Company Sub Department
    |--------------------------------------------------------------------------
    |
    | The value inputted in the field is equivalent to the clients basic information
    | To change the values, you need to update the environment variable for it to work
    */

    'subdepartment' => env('COMPANY_ALTDEPARTMENT','Secondary Department'),

    /*
    |--------------------------------------------------------------------------
    | Company Logo
    |--------------------------------------------------------------------------
    |
    | The value inputted in the field is equivalent to the clients basic information
    | To change the values, you need to update the environment variable for it to work
    */

    'logo' => env('COMPANY_LOGO','Main Logo Link'),

    /*
    |--------------------------------------------------------------------------
    | Company Sub Logo
    |--------------------------------------------------------------------------
    |
    | The value inputted in the field is equivalent to the clients basic information
    | To change the values, you need to update the environment variable for it to work
    */

    'altlogo' => env('COMPANY_ALTLOGO','Secondary Logo Link'),

    /*
    |--------------------------------------------------------------------------
    | Company Details
    |--------------------------------------------------------------------------
    |
    | The value inputted in the field is equivalent to the clients basic information
    | To change the values, you need to update the environment variable for it to work
    */

    'local' => [
        'constant' => env('LOCAL_CONST'),
    ],

];
<?php

namespace App\Http\Modules\Account;

use Auth;
use Hash;
use Validator;
use Illuminate\Validation\Rule;

trait PasswordManager
{
    
    /**
     * Gets the current password defined by 
     * $defaultPassword variable
     */
    public function getDefaultPassword()
    {
        return self::$defaultPassword;
    }
}
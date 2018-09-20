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

    /**
     * Assign the new password to the current password
     * hash the password
     *
     * @param String $newPassword
     * @return object
     */
    public function passwordReset(String $newPassword)
    {
		$this->password = Hash::make($newpassword);
        
        return $this;
    }
}
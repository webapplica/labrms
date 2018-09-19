<?php

namespace App\Http\Modules\Account;

use Auth;
use Hash;
use Validator;
use Illuminate\Validation\Rule;

trait PasswordManager
{
    
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
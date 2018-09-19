<?php

namespace App\Http\Modules\Account;

use Auth;
use Hash;
use Validator;
use Illuminate\Validation\Rule;

trait PasswordManager
{
    
    /**
     * Change the password of the given user
     *
     * @param String $currentPassword
     * @param String $newPassword
     * @return object
     */
    public function changePassword(String $currentPassword, String $newPassword)
    {
        
		$args = [
			'Current Password' => $currentPassword,
			'New Password' => $newPassword
		];

		if($validator->fails()) {
			return back()->withInput()->withErrors($validator);
		}

		$this->password = Hash::make($newpassword);
        $this->save();
        
        return $this;
    }
}
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

		$validator = Validator::make($args, [
            'Current Password'=>'required|min:8|max:50',
            'New Password'=> [
                'required',
                'min:8',
                'max:50',
                Rule::notIn([ $currentPassword ]),
            ]
        ]);

		if($validator->fails()) {
			return back()->withInput()->withErrors($validator);
		}

		$this->password = Hash::make($newpassword);
        $this->save();
        
        return $this;
    }
}
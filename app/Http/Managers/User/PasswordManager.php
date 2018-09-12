<?php

namespace App\Http\Managers\User;

use Auth;
use Hash;
use Validator;

trait PasswordManager
{
    
    /**
     * Change the password of the given user
     *
     * @param String $currentPassword
     * @param String $newPassword
     * @return object
     */
    protected function changePassword(String $currentPassword, String $newPassword)
    {
        
		$args = [
			'Current Password' => $currentPassword,
			'New Password' => $newPassword
		];

		$rules = [
			'Current Password'=>'required|min:8|max:50',
			'New Password'=>'required|min:8|max:50'
		];

		$validator = Validator::make($args, $rules);
		if($validator->fails() || $error) {
			return back()->withInput()->withErrors($validator);
		}

		if(Hash::check($currentPassword, Auth::user()->password)) {
			if(Hash::check($newPassword, Auth::user()->password)) {
				session()->flash('error-message','Your New Password must not be the same as your Old Password');
				return back()->withInput()->withErrors($validator);
			}
		} else {

			session()->flash('error-message','Incorrect Password');
			return back()->withInput();
		}

		$this->password = Hash::make($newpassword);
        $this->save();
        
        return $user;
    }
}
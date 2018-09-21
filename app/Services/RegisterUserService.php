<?php

namespace App\Services;

use App\Models\User;

class RegisterUserService
{
	public function make(RegisterUser $request)
	{
		$user = User::create([
			'username' => $request->username,
			'password' => $request->password,
			'lastname' => $request->firstname,
			'firstname' => $request->firstname,
			'middlename' => $request->middlename,
			'email' => $request->email,
			'contactnumber' => $request->contactnumber,
		]);

		return $user;
	}
}
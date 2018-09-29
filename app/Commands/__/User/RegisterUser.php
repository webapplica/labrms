<?php

namespace App\Commands\User;

use App\Models\User;
use Illuminate\Http\Request;

class RegisterUser
{
	protected $request;
	const DEFAULT_STATUS = 1;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function handle(User $user)
	{
		$request = $this->request;
		User::create([
			'firstname' => $request->firstname,
			'middlename' => $request->middlename,
			'lastname' => $request->lastname,
			'email' => $request->email,
			'contactnumber' => $request->contactnumber,
			'username' => $request->username,
			'password' => $user->getDefaultPassword(),
			'accesslevel' => $request->accesslevel,
			'type' => $request->type,
			'status' => DEFAULT_STATUS
		]);
	}
}
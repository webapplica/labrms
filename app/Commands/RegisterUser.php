<?php

namespace App\Commands;

use App\Commands;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterUser
{
	protected $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function handle(User $user)
	{
		$user->firstname = $this->request->firstname;
		$user->middlename = $this->request->middlename;
		$user->lastname = $this->request->lastname;
		$user->email = $this->request->email;
		$user->contactnumber = $this->request->contactnumber;
		$user->username = $this->request->username;
		$user->password = $user->getDefaultPassword();
		$user->accesslevel = $this->request->accesslevel;
		$user->type = $this->request->type;
		$user->status = 1;
		$user->save();
	}
}
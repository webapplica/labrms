<?php

namespace App\Commands\User;

use App\Models\User;
use Illuminate\Http\Request;

class UpdateUser
{
	protected $user;
	protected $request;

	public function __construct(Request $request, $id)
	{
		$this->request = $request;
		$this->user = User::find($id);
	}

	public function handle()
	{
		$request = $this->request;
		$this->user->update([
			'firstname' => $request->firstname,
			'middlename' => $request->middlename,
			'lastname' => $request->lastname,
			'email' => $request->email,
			'contactnumber' => $request->contactnumber,
			'username' => $request->username,
			'type' => $request->type,
		]);
	}
}
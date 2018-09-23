<?php

namespace App\Commands\User;

use App\Models\User;
use Illuminate\Http\Request;

class UpdateUser
{
	protected $request;

	public function __construct(Request $request, $id)
	{
		$this->request = $request;
		$this->user = User::find($id);
	}

	public function handle()
	{
		$this->user->firstname = $this->request->firstname;
		$this->user->middlename = $this->request->middlename;
		$this->user->lastname = $this->request->lastname;
		$this->user->email = $this->request->email;
		$this->user->contactnumber = $this->request->contactnumber;
		$this->user->username = $this->request->username;
		$this->user->type = $this->request->type;
		$this->user->save();
	}
}
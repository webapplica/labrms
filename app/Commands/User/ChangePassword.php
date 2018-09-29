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
		$currentPassword = filter_var($this->request->current_password, FILTER_SANITIZE_STRING);
        $newPassword = filter_var($this->request->new_password, FILTER_SANITIZE_STRING);
        
        $user->password = Hash::make($newPassword);
        $user->save();
	}
}
		
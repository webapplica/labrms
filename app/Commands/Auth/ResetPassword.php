<?php

namespace App\Commands\Auth;

use App\Models\User;
use Illuminate\Http\Request;

class ResetPassword
{

	protected $id;

	/**
	 * constructor
	 */
	public function __construct(int $id)
	{
		$this->id = $id;
	}

	/**
	 * reset password logic
	 * 
	 * @return [type] [description]
	 */
	public function handle()
	{
		$user = User::findOrFail($id);
		$user->password = $this->encryptPassword($user->getDefaultPassword());
		$user->save();
	}

    /**
     * Assign the new password to the current password
     * hash the password
     *
     * @param String $newPassword
     * @return object
     */
    public function encryptPassword(String $newPassword)
    {
		return Hash::make($newPassword);
    }
}
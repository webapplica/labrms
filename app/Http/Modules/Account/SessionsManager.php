<?php

namespace App\Http\Modules\Account;

trait SessionsManager
{

	/**
	 * Clears authentication and session of the user
	 *
	 * @return void
	 */
	public static function clear()
	{
		if(Auth::check()) {
			$user = Auth::user();
			Auth::logout();
		}

		Session::flush();
		return isset($user) ? $user : [];
	}
}
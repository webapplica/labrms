<?php

namespace App\Http\Modules\Account;

use Auth;
use Session;

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

	/**
	 * Check if the current account is activated
	 * if not logs the user out of the system
	 * 
	 * @return
	 */
	public function isActivated()
	{
        if($this->status == 0) {
            return redirect('logout', __('account.activation_required'));
        }

        return $this;
	}
}
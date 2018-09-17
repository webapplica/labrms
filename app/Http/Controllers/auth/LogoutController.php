<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Session;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LogoutController extends Controller
{

	/**
	 * Logs the user out of the system
	 *
	 * @param Request $request
	 * @return void
	 */
	public function logout(Request $request)
	{
		if(Auth::check()) {
			User::clear();
			session()->flash('success-message', __('auth.logout'));
		}

		return redirect('login');
	}
}
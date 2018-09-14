<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Illuminate\Http\Request;
use App\Http\Classes\LoginRequest;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{

	/**
	 * Form for login
	 * 
	 * @param  Request $request 
	 * @return login view
	 */
	public function form(Request $request)
	{
		return view('auth.login', [
			'title' => 'Login',
			'isLoginPage' => true,
			'bodyColor' => '#22313f',
		]);
	}

	/**
	 * Validates the users information
	 * Logs the user into the system if allowed
	 * 
	 * @return
	 */
	public function login(LoginRequest $request)
	{
		$username = filter_var($request->get('username'), FILTER_SANITIZE_STRING);
		$password = filter_var($request->get('password'), FILTER_SANITIZE_STRING);
		
		$user = [	
			'username' => $username,
			'password' => $password
 		];

		if(Auth::attempt($user)) {
			session()->flash('success-message','Invalid login credentials');
			return redirect('dashboard')->with('success-message', __('auth.success'));
 		}

		return redirect('login')->with('error-message', __('auth.failed'));
	}
}
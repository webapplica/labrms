<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
	private $args = [
        'status' => 'error',
        'errors' => [],
        'message' => 'Error occured while processing your request',
        'title' => 'Error!',
    ];

	private $statusCode = 500;

	/**
	 * Form for login
	 * 
	 * @param  Request $request 
	 * @return login view
	 */
	public function form(Request $request)
	{
		return view('auth.login');
	}

	/**
	 * Validates the users information
	 * Logs the user into the system if allowed
	 * 
	 * @return
	 */
	public function login(Request $request)
	{
		$username = $this->sanitizeString($request->get('username'), FILTER_SANITIZE_STRING);
		$password = filter_var($request->get('password'), FILTER_SANITIZE_STRING);
		$allowed = false;
		
		$user = [	
			'username' => $username,
			'password' => $password
 		];

		if(Auth::attempt($user)) {
 			$allowed = true;
 		}

		if($request->ajax()) {
			if($allowed) {

	            $this->args = [
	                'status' => 'success',
	                'errors' => [],
	                'message' => 'User is now permitted to access the system. Redirecting....',
	                'title' => 'Success!',
	            ];

	            $this->statusCode = 200;
			} 

            $this->jsonResponse($this->args, $this->statusCode);
		}

 		if($allowed) {
			return redirect('dashboard');
 		}

		session()->flash('error-message','Invalid login credentials');
		return redirect('login');
	}
}
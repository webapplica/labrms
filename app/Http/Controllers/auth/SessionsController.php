<?php

namespace App\Http\Controllers\auth;

use App;
use Auth;
use Hash;
use Session;
use App\User;
use Validator;
use App\TicketView;
use App\Reservation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

class SessionsController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		return view('pagenotfound');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		return view('login');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		if($request->ajax()) {
			$username = $this->sanitizeString($request->get('username'));
			$password = $this->sanitizeString($request->get('password'));
 		
			$user = array(	
				'username' => $username,
				'password' => $password
	 		);

			if(Auth::attempt($user))
			{
	 			return 'success';
	 		}else{
	 			return 'balakajan';
	 		}
		}

		$username = $this->sanitizeString($request->get('username'));
		$password = $this->sanitizeString($request->get('password'));

 		$user = User::where('username','=',$username)->count();

 		if($user == 0)
 		{
			Session::flash('error-message','Invalid login credentials');
			return redirect('login');
 		}

 		if($user->status == '0')
 		{

			Session::flash('error-message','Account Inactive. Contact the administrator to activate your account');
			return redirect('login');

 		}
 		
		$user = array(	
			'username' => $username,
			'password' => $password
 		);

		if(Auth::attempt($user))
		{
			return redirect('dashboard');
		}

		Session::flash('error-message','Invalid login credentials');
		return redirect('login');

	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request)
	{
		$person = Auth::user(); 
		$reservation = App\Reservation::withInfo()->user(Auth::user()->id)->get()->count();
		$approved = App\Reservation::withInfo()->approved()->user(Auth::user()->id)->get()->count();
		$disapproved = App\Reservation::withInfo()->disapproved()->user(Auth::user()->id)->get()->count();
		$claimed = App\Reservation::unclaimed()->user(Auth::user()->id)->get()->count();
		$tickets = App\Ticket::selfAuthored()->get()->count();
		$assigned = App\Ticket::selfAssigned(Auth::user()->id)->findByType('Complaint')->count();
		$complaints = App\Ticket::selfAuthored()->findByType('Complaint')->count();

		return view('user.index')
			->with('person',$person)
			->with('reservation',$reservation)
			->with('tickets',$tickets)
			->with('approved',$approved)
			->with('disapproved',$disapproved)
			->with('complaints',$complaints)
			->with('assigned',$assigned)
			->with('claimed',$claimed);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $request)
	{
		return view('user.edit');
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request)
	{
		$password = $this->sanitizeString($request->get('password'));
		$newpassword = $this->sanitizeString($request->get('newpassword'));

		$user = User::find(Auth::user()->id);

		$validator = Validator::make(
				[
					'Current Password'=>$password,
					'New Password'=>$newpassword
				],
				[
					'Current Password'=>'required|min:8|max:50',
					'New Password'=>'required|min:8|max:50'
				]
			);

		if( $validator->fails() )
		{
			return redirect()->back()
				->withInput()
				->withErrors($validator);
		}

		//verifies if password inputted is the same as the users password
		if(Hash::check($password,Auth::user()->password))
		{

			//verifies if current password is the same as the new password
			if(Hash::check($newpassword,Auth::user()->password)) {
				Session::flash('error-message','Your New Password must not be the same as your Old Password');
				return redirect()->back()
					->withInput()
					->withErrors($validator);
			} else {

				$user->password = Hash::make($newpassword);
				$user->save();
			}
		} else {

			Session::flash('error-message','Incorrect Password');
			return redirect()->back()
				->withInput();
		}

		Session::flash('success-message','Password updated');
		return redirect()->back();
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request)
	{
		Session::flush();
		Auth::logout();
		return redirect('login');
	}

	/**
	 * Returns the function for resetting the user
	 *
	 * @return void
	 */
	public function getResetForm(Request $request)
	{
		return view('user.reset');
	}

	/**
	 * Resets user password
	 *
	 * @return void
	 */
	public function reset(Request $request)
	{
		
	}

}

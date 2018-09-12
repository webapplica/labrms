<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Hash;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SessionsController extends Controller 
{

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request)
	{
		// $person = Auth::user(); 
		// $reservation = App\Reservation::withInfo()->user(Auth::user()->id)->get()->count();
		// $approved = App\Reservation::withInfo()->approved()->user(Auth::user()->id)->get()->count();
		// $disapproved = App\Reservation::withInfo()->disapproved()->user(Auth::user()->id)->get()->count();
		// $claimed = App\Reservation::unclaimed()->user(Auth::user()->id)->get()->count();
		// $tickets = App\Ticket::selfAuthored()->get()->count();
		// $assigned = App\Ticket::selfAssigned(Auth::user()->id)->findByType('Complaint')->count();
		// $complaints = App\Ticket::selfAuthored()->findByType('Complaint')->count();

		// return view('user.index')
		// 	->with('person',$person)
		// 	->with('reservation',$reservation)
		// 	->with('tickets',$tickets)
		// 	->with('approved',$approved)
		// 	->with('disapproved',$disapproved)
		// 	->with('complaints',$complaints)
		// 	->with('assigned',$assigned)
		// 	->with('claimed',$claimed);
		return view('dashboard.admin.index');
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
		$currentPassword = filter_var($request->get('password'), FILTER_SANITIZE_STRING);
		$newPassword = filter_var($request->get('newpassword'), FILTER_SANITIZE_STRING);
		
		$user->changePassword($currentPassword, $newPassword);
		
		session()->flash('success-message','Password updated');
		return back();
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	// public function destroy(Request $request)
	// {
	// 	Session::flush();
	// 	Auth::logout();

	// 	return redirect('login');
	// }

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
		return true;
	}

}

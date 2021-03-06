<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Hash;
use Validator;
use App\Models\User;
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
		$this->dispatch(new ChangePassword($request));
		return back()->with('success-message', __('account.successful_password_update'));
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
		return true;
	}

}

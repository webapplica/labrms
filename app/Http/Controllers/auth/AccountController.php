<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Hash;
use Session;
use Validator;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;

class AccountsController extends Controller
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if($request->ajax()) {
			return datatables(User::all())->toJson();
		}

		return view('account.index');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		return view('account.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$lastname = $this->clean($request->lastname);
		$firstname = $this->clean($request->get('firstname'));
		$middlename = $this->clean($request->get('middlename'));
		$username = $this->clean($request->get('username'));
		$contactnumber = $this->clean($request->get('contactnumber'));
		$email = $this->clean($request->get('email'));
		$password = $this->clean($request->get('password'));
		$type = $this->clean($request->get('type'));

		$validator = Validator::make([
			'Last name' => $lastname,
			'First name' => $firstname,
			'Middle name' => $middlename,
			'Username' => $username,
			'Contact number' => $contactnumber,
			'Email' => $email,
			'Password' => $password
		], User::$rules);

		if($validator->fails()) {
			return back()->withErrors($validator)->withInput();
		}

		User::insertValidation()->save();

		User::createRecord($username,$password,$lastname,$firstname,$middlename,$contactnumber,$email,$type);

		Session::flash("success-message","Account successfully created!");
		return redirect('account');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $id)
	{
		$user = User::find($id);
		return view('account.show')
			->with('person',$user);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $request, $id)
	{
		$user = User::find($id);
		return view('account.update')
			->with('user',$user);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$id = $this->sanitizeString($request->get('id'));
		$lastname = $this->sanitizeString($request->get('lastname'));
		$firstname = $this->sanitizeString($request->get('firstname'));
		$middlename = $this->sanitizeString($request->get('middlename'));
		$contactnumber = $this->sanitizeString($request->get('contactnumber'));
		$email = $this->sanitizeString($request->get('email'));
		$type = $this->sanitizeString($request->get('type'));
		$username = $this->sanitizeString($request->get('username'));

		$validator = Validator::make([
			'last name' => $lastname,
			'first name' => $firstname,
			'middle name' => $middlename,
			'contact number' => $contactnumber,
			'email' => $email,
			'type' => $type
		], User::$updateRules);

		if($validator->fails())
		{
			return redirect('login')
				->withInput()
				->withErrors($validator);
		}

		User::updateRecord($id,$username,$lastname,$firstname,$middlename,$contactnumber,$email,$type);

		Session::flash('success-message','Account information updated');
		return redirect('account');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		if($request->ajax()){
			try{

				$user = User::select('id')->get();
				if($id == Auth::user()->id){
					return json_encode('self');
				}else if(count($user) <= 1){
					return json_encode('invalid');
				}else{
					$user = User::find($id);
					$user->delete();
					return json_encode('success');
				}
			} catch (Exception $e) {}
		}
		Session::flash('success-message', __('account.task_performed_successfully'));
		return redirect('account');
	}

	/**
	 * Display a list of deleted account
	 *
	 * @return Response
	 */
	public function retrieveDeleted(Request $request)
	{
		if($request->ajax()) {
			return datatables(User::onlyTrashed()->get())->toJson();
		}

		return view('account.restore')
			->with('user', $user);

	}

	/**
	 *Restore Deleted Account
	 *
	 *
	 */

	public function restore(Request $request, $id)
	{
		$id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
		$user = User::onlyTrashed()->find($id);
		$user->restore();

		Session::flash('success-message',"Account restored!");
        return redirect('account/deleted');
	}

	/**
	 * Activate or deactivate account
	 *
	 *@param  int  $type
	 *@param  int  $id
	 * @return Response
	 */
	public function activate(Request $request, $id)
	{
		$response = [];
		$code = 200;
		if($request->ajax()) {
			$type = $this->clean($request->get('type'));
			$id = $this->clean($request->get('id'));
			$user = User::find($id);

			if($id == Auth::user()->id) {
				$response = [
					'message' => __('account.activation.invalid'),
				];

				$code = 500;
			}

			if($type == 'activate') {
				$user->status = 1;

				$response = [
					'message' => __('account.activated'),
				];
			}

			if($type == 'deactivate') {
				$user->status = 0;

				$response = [
					'message' => __('account.deactivated'),
				];
			}

			$user->save();
			return response()->json($response, $code);
		}
	}
	
	/**
	 * Change User Password to Default '12345678'
	 *
	 * user id
	 *@param  int  $id
	 */
	public function resetPassword()
	{
		if($request->ajax())
		{
			$id = $request->id;
		 	$user = User::find($request->id)->passwordReset();

		 	return response([
		 		'message' => __('account.task_performed_successfully')
		 	], 200);
		}
	}

	public function changeAccessLevel()
	{
		$id = $this->clean($request->id);
		$newAccessLevel = $this->clean($request->newaccesslevel);

		if(Auth::user()->accesslevel != User::getAdminId()) {
			Session::flash('error-message', __('account.not_enough_priviledge'));
		}
			
		$user = User::find($id)->updateAccessLevel($newAccessLevel)->save();
		Session::flash('success-message', __('account.task_performed_successfully'));
		return redirect('account');
	}

	/**
	*
	*	return value: 'data' => array(users)
	*	
	*	@return list of username
	*
	*/
	public function getAllUsers()
	{
		return datatables([ 'data' => User::all() ])->toJson();
	}

	/**
	*
	*	laboratory users ranges from 0 - 2
	*	Not included the current user
	*	0 - laboratory head
	*	1 - laboratory assistant
	*	2 - laboratory staff
	*	
	*	@return laboratory users
	*
	*/
	public function getAllLaboratoryUsersExceptCurrentUser()
	{
		return datatables([ 'data' => User::allLaboratoryUsersExceptCurrentUser()->get() ])->toJson();
	}
}

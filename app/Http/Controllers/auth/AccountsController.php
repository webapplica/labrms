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
		$lastname = $this->sanitizeString($request->get('lastname'));
		$firstname = $this->sanitizeString($request->get('firstname'));
		$middlename = $this->sanitizeString($request->get('middlename'));
		$username = $this->sanitizeString($request->get('username'));
		$contactnumber = $this->sanitizeString($request->get('contactnumber'));
		$email = $this->sanitizeString($request->get('email'));
		$password = $this->sanitizeString($request->get('password'));
		$type = $this->sanitizeString($request->get('type'));

		$validator = Validator::make([
			'Last name' => $lastname,
			'First name' => $firstname,
			'Middle name' => $middlename,
			'Username' => $username,
			'Contact number' => $contactnumber,
			'Email' => $email,
			'Password' => $password
		],User::$rules);

		if($validator->fails())
		{
			return redirect('account/create')
				->withErrors($validator)
				->withInput();
		}

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
		if(isset($id)){
			$user = User::find($id);
			return view('account.update')
				->with('user',$user);
		}
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

		$user = User::find($id);
		$user->delete();

		Session::flash('success-message','An account has been successfully deleted.');
		return redirect('account/deleted');
	}

	/**
	 * Display a list of deleted account
	 *
	 * @return Response
	 */
	public function retrieveDeleted()
	{
		if($request->ajax()){
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
		if($request->ajax()) {
			if($id == Auth::user()->id) {
				return json_encode('self');
			} else {

				$type = $this->sanitizeString($request->get('type'));
				$user = User::find($id);

				if($type == 'activate') {
					$user->status = 1;
					$user->save();
					return json_encode('activated');
				} else if($type == 'deactivate') {
					$user->status = 0;
					$user->save();
					return json_encode('deactivated');
				}
			}
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
			$id = $this->sanitizeString($request->get('id'));
		 	$user = User::find($id);
		 	$user->password = Hash::make('12345678');
		 	$user->save();

		 	return json_encode('success');
		}
	}

	public function changeAccessLevel()
	{
		$id = $this->sanitizeString($request->get("id"));
		$access = $this->sanitizeString($request->get('newaccesslevel'));

		try {

			if(Auth::user()->accesslevel != 0) {

				Session::flash('error-message','You do not have enough priviledge to switch to this level');
				return redirect('account');
			}
			
			$user = User::find($id);
			$user->accesslevel = $access;
			$user->save();

			Session::flash('success-message','Access Level Switched');
			return redirect('account');
		} catch (Exception $e){

			Session::flash('error-message','Error occurred while switching access level');
			return redirect('account');
		}
	}

	/**
	*
	*	@return list of username
	*	return value: 'data' => array(users)
	*
	*/
	public function getAllUsers()
	{
		if($request->ajax()) {
			$user = User::all();
			return json_encode([ 'data' => $user ]);
		}
	}

	/**
	*
	*	@return laboratory users
	*	laboratory users ranges from 0 - 2
	*	0 - laboratory head
	*	1 - laboratory assistant
	*	2 - laboratory staff
	*
	*/
	public function getAllLaboratoryUsers()
	{
		if($request->ajax()) {
			/**
			*
			*	Note: Current user is not included
			*
			*/
			$user = User::whereIn('accesslevel',[0,1,2])
					->where('id','!=',Auth::user()->id)
					->get();
			return json_encode([ 'data' => $user ]);
		}
	}
}

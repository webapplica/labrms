<?php

namespace App\Http\Controllers\Maintenance;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
	private $viewBasePath = 'maintenance.account.';
	private $baseUrl = 'account';

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

		return view( $this->viewBasePath . 'index');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		return view( $this->viewBasePath . 'create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{

		$this->validate($request, [
			'username' => 'required_with:password|min:4|max:20|unique:users,username',
			'firstname' => 'required|between:2,100|string',
			'middlename' => 'min:2|max:50|string',
			'lastname' => 'required|min:2|max:50|string',
			'contactnumber' => 'required|size:11|string',
			'email' => 'required|email'
		]);

		User::create($request);

		return redirect('account')->with('success-message', __('tasks.success'));
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $id)
	{
		$args = [ 'id' => $id ];
		$this->validate($args, [
			'id' => 'required|exists:users,id|not_in:' . Auth::user()->id . '|numeric',
		]);

		$user = User::find($id);
		return view( $this->viewBasePath . 'show')
					->with('person', $user);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $request, $id)
	{
		$args = [ 'id' => $id ];
		$this->validate($args, [
			'id' => 'required|exists:users,id|not_in:' . Auth::user()->id . '|numeric',
		]);

		$user = User::find($id);
		return view( $this->viewBasePath . 'update')
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
		$user = User::find($id);

		$this->validate($request + ['id' => $id], [
			'id' => 'required|integer|exists:users,id',
			'username' => 'required_with:password|min:4|max:20|unique:users,username,' . $user->username . ',username',
			'firstname' => 'required|between:2,100|string',
			'middlename' => 'min:2|max:50|string',
			'lastname' => 'required|min:2|max:50|string',
			'contact_number' => 'required|size:11|string',
			'email' => 'required|email'
		]);

		$user->lastname = $request->lastname;
		$user->firstname = $request->firstname;
		$user->middlename = $request->middlename;
		$user->contact_number = $request->contact_number;
		$user->email = $request->email;
		$user->username = $request->username;
		$user->save();

		return redirect('account')->with('success-message', __('tasks.success'));
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		$args = [ 'id' => $id ];
		$this->validate($args, [
			'id' => 'required|exists:users,id|not_in:' . Auth::user()->id . '|numeric',
		]);

		User::find($id)->delete();

		if($request->ajax()) {
			return response()->json([
				'message' => __('tasks.success')
			], 200);
		}
		
		return redirect('account')->with('success-message', __('tasks.success'));
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

		return view( $this->viewBasePath . 'restore')
			->with('user', $user);

	}

	/**
	 *Restore Deleted Account
	 *
	 *
	 */

	public function restore(Request $request, $id)
	{
		$args = [ 'id' => $id ];
		$this->validate($args, [
			'id' => 'required|exists:users,id|not_in:' . Auth::user()->id . '|numeric',
		]);

		User::onlyTrashed()->find($id)->restore();

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
		$args = [ 'id' => $id ];
		$this->validate($args, [
			'id' => 'required|exists:users,id|not_in:' . Auth::user()->id . '|numeric',
		]);

		$user = User::find($id)->toggleStatusByAction($type)->save();
		return redirect($this->baseUrl);
	}
	
	/**
	 * Change User Password to Default '12345678'
	 *
	 * user id
	 *@param  int  $id
	 */
	public function resetPassword(Request $request)
	{
		$validator = $this->validate($request, [
			'id' => 'integer|exists:users,id|required',
            'current_password'=>'required|min:8|max:50',
            'new_password'=> [
                'required',
                'min:8',
                'max:50',
                Rule::notIn([ $request->current_password ]),
            ],
        ]);

	 	$user = User::find($request->id)->passwordReset()->save();

		if($request->ajax()) {
		 	return response([
		 		'message' => __('account.task_performed_successfully')
		 	], 200);
		}

		return redirect('account')->with('success-message', __('tasks.success'));
	}

	public function changeAccessLevel()
	{
		$id = $request->id;
		$newAccessLevel = $this->clean($request->new_access_level);

		$this->validate($request, [
			'id' => 'required|integer|exists:user,id',
			'new_access_level' => 'required|integer',
		]);

		if(Auth::user()->accesslevel != User::getAdminId()) {
			return back()->with('error-message', __('account.not_enough_priviledge'));
		}
			
		$user = User::find($id)->updateAccessLevel($newAccessLevel)->save();
		return redirect('account')->with('success-message', __('account.task_performed_successfully'));
	}
}

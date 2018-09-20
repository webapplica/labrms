<?php

namespace App\Http\Controllers\Maintenance;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountRequest\AccountEditRequest;
use App\Http\Requests\AccountRequest\AccountShowRequest;
use App\Http\Requests\AccountRequest\AccountStoreRequest;
use App\Http\Requests\AccountRequest\AccountUpdateRequest;
use App\Http\Requests\AccountRequest\AccountDestroyRequest;

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
	public function create(Request $request, User $user)
	{
		return view( $this->viewBasePath . 'create')
					->with('roles', $user->camelCaseRoles());
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(AccountStoreRequest $request)
	{
		User::create($request->toArray());
		return redirect('account')->with('success-message', __('tasks.success'));
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(AccountShowRequest $request, $id)
	{
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
	public function edit(AccountEditRequest $request, $id)
	{
		$user = User::find($id);
		return view( $this->viewBasePath . 'update')
					->with('user',$user)
					->with('roles', $user->camelCaseRoles());
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(AccountUpdateRequest $request, $id)
	{
		User::find($id)->update($request);
		return redirect('account')->with('success-message', __('tasks.success'));
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(AccountDestroyRequest $request, $id)
	{
		User::find($id)->delete();
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

	public function restore(AccountDestroyRequest $request, $id)
	{
		User::onlyTrashed()->find($id)->restore();
        return redirect('account/deleted')->with('success-message', __('tasks.success'));
	}

	/**
	 * Activate or deactivate account
	 *
	 *@param  int  $type
	 *@param  int  $id
	 * @return Response
	 */
	public function activate(AccountShowRequest $request, $id)
	{
		$user = User::find($id)->toggleStatusByAction($type)->save();
		return redirect($this->baseUrl);
	}
	
	/**
	 * Change User Password to Default '12345678'
	 *
	 * user id
	 *@param  int  $id
	 */
	public function resetPassword(PasswordResetRequest $request)
	{
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
		$user = User::find($id)->updateAccessLevel($newAccessLevel)->save();
		return redirect('account')->with('success-message', __('account.task_performed_successfully'));
	}
}

<?php

namespace App\Http\Controllers\Maintenance;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Commands\User\UpdateUser;
use App\Commands\User\DeleteUser;
use App\Commands\User\RegisterUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountRequest\AccountStoreRequest;
use App\Http\Requests\AccountRequest\AccountUpdateRequest;
use App\Http\Requests\AccountRequest\PasswordResetRequest;

class AccountController extends Controller
{
	private $viewBasePath = 'maintenance.account.';
	private $baseUrl = 'account';
	private $user;

	public function __construct(User $user)
	{
		$this->user = $user;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if($request->ajax()) {
			return datatables($this->user->get())->toJson();
		}

		return view($this->viewBasePath . 'index');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request, User $user)
	{
		return view($this->viewBasePath . 'create')
					->with('roles', $user->camelCaseRoles())
					->with('types', $user->types());
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(AccountStoreRequest $request)
	{
		$this->dispatch(new RegisterUser($request));
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
		return view($this->viewBasePath . 'show')
				->with('person', $this->user->findOrFail($id));
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $request, $id)
	{
		$user = $this->user->findOrFail($id);
		return view($this->viewBasePath . 'update')
					->with('user', $user)
					->with('roles', $user->camelCaseRoles())
					->with('types', $user->types());
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(AccountUpdateRequest $request, $id)
	{
		$this->dispatch(new UpdateUser($request, $id));
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
		$this->dispatch(new DeleteUser($id));
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

		return view($this->viewBasePath . 'restore');

	}

	/**
	 *Restore Deleted Account
	 *
	 *
	 */

	public function restore(Request $request, $id)
	{
		User::onlyTrashed()->findOrFail($id)->restore();
        return redirect('account/deleted')->with('success-message', __('tasks.success'));
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
		$user = User::findOrFail($id)->toggleStatusByAction($type)->save();
		return redirect($this->baseUrl);
	}
	
	/**
	 * Change User Password to Default '12345678'
	 *
	 * user id
	 *@param  int  $id
	 */
	public function resetPassword(PasswordResetRequest $request, $id)
	{
	 	$this->dispatch(new ResetPassword($id));
		return redirect('account')->with('success-message', __('tasks.success'));
	}

	public function changeAccessLevel()
	{
		$user = User::find($id)->updateAccessLevel($newAccessLevel)->save();
		return redirect('account')->with('success-message', __('account.task_performed_successfully'));
	}
}

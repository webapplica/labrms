<?php

namespace App\Http\Controllers\inventory\workstation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssignmentController extends Controller
{
	/**
    *	Deploying workstation to a location
    *
	*	@param $room accepts room name
	*	@param $workstation accepts workstation id list
	*
	*/
	public function deploy(Request $request, $id)
	{
		$this->dispatch(new DeployWorkstation($request, $id));
		return redirect('workstation')->with('success-message', __('tasks.success'));
	}

	/**
	*
    *	Transfering workstation to another location
    *
	*	@param $room accepts room name
	*	@param $workstation accepts workstation id list
	*
	*/
	public function transfer(Request $request, $id)
	{
		$this->dispatch(new TransferWorkstation($request, $id));
		return redirect('workstation')->with('success-message', __('tasks.success'));
	}
}

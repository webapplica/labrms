<?php

namespace App\Http\Controllers\inventory\workstation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeploymentController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function get()
	{
		return view('workstation.deployment.create');
    }
    
	/**
    *	Deploying workstation to a location
    *
	*	@param $room accepts room name
	*	@param $workstation accepts workstation id list
	*
	*/
	public function store(Request $request, $id)
	{
		$this->dispatch(new DeployWorkstation($request, $id));
		return redirect('workstation')->with('success-message', __('tasks.success'));
	}
}

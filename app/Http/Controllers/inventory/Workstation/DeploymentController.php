<?php

namespace App\Http\Controllers\inventory\workstation;

use App\Models\Room\Room;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Workstation\Workstation;
use App\Commands\Workstation\DeployWorkstation;
use App\Http\Requests\WorkstationRequest\WorkstationDeployRequest;

class DeploymentController extends Controller
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function get($id)
	{
		$workstation = Workstation::findOrFail($id);
		$rooms = Room::all();

		return view('workstation.deployment.create', compact('workstation', 'rooms'));
    }
    
	/**
    *	Deploying workstation to a location
    *
	*	@param $room accepts room name
	*	@param $workstation accepts workstation id list
	*
	*/
	public function store(WorkstationDeployRequest $request, $id)
	{
		$this->dispatch(new DeployWorkstation($request, $id));
		return redirect('workstation')->with('success-message', __('tasks.success'));
	}
}

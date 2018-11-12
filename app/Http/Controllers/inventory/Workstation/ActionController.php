<?php

namespace App\Http\Controllers\Inventory\Workstation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Workstation\Workstation;
use App\Commands\Workstation\AddAction;

class ActionController extends Controller
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function get($id)
	{
		$workstation = Workstation::findOrFail($id);
		return view('workstation.action.create', compact('workstation'));
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
		$this->dispatch(new AddAction($request, $id));
		return redirect('workstation')->with('success-message', __('tasks.success'));
	}
}

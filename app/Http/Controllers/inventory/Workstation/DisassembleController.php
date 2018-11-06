<?php

namespace App\Http\Controllers\inventory\workstation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Commands\Workstation\DisassembleWorkstation;

class DisassembleController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function get($id)
	{
        return view('errors.404');
		$workstation = Workstation::findOrFail($id);
		return view('workstation.disassemble.create');
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
        return view('errors.404');
		$this->dispatch(new DisassembleWorkstation($request, $id));
		return redirect('workstation')->with('success-message', __('tasks.success'));
	}
}

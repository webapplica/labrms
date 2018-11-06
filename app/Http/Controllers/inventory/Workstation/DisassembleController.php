<?php

namespace App\Http\Controllers\inventory\workstation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Workstation\Workstation;
use App\Commands\Workstation\DisassembleWorkstation;
use App\Http\Requests\WorkstationRequest\WorkstationDisassembleRequest;

class DisassembleController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function get($id)
	{
		$workstation = Workstation::findOrFail($id);
		return view('workstation.disassemble.create', compact('workstation'));
    }
    
	/**
    *	Deploying workstation to a location
    *
	*	@param $room accepts room name
	*	@param $workstation accepts workstation id list
	*
	*/
	public function store(WorkstationDisassembleRequest $request, $id)
	{
		$this->dispatch(new DisassembleWorkstation($request, $id));
		return redirect('workstation')->with('success-message', __('tasks.success'));
	}
}

<?php

namespace App\Http\Controllers\inventory\workstation;

use App\Models\Room\Room;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Workstation\Workstation;
use App\Http\Requests\WorkstationRequest\WorkstationTransferRequest;

class TransferController extends Controller
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

		return view('workstation.transfer.create', compact('workstation', 'rooms'));
    }

	/**
	*
    *	Transferring workstation to another location
    *
	*	@param $room accepts room name
	*	@param $workstation accepts workstation id list
	*
	*/
	public function store(WorkstationTransferRequest $request, $id)
	{
		$this->dispatch(new TransferWorkstation($request, $id));
		return redirect('workstation')->with('success-message', __('tasks.success'));
	}
}

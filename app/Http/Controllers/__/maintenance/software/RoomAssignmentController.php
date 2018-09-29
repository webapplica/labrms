<?php

namespace App\Http\Controllers\maintenance\software;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoomAssignmentController extends Controller
{

	/**
	 * Displays the index page for rooms associated with the 
	 * software given
	 * 
	 * @param  Request $request 
	 * @param  int  $id      
	 * @return           
	 */
	public function index(Request $request, $id)
	{
		$software = Software::findOrFail($id);
		if($request->ajax()) {
			return datatables($software->room());
		}

		return view('software.room.assign', compact('software'));
	}

	/**
	 * Assigns the software to the list of rooms
	 * 
	 * @param  Request $request [description]
	 * @param  int  $id      
	 * @return
	 */
	public function assign(Request $request, $id)
	{	
		$this->dispatch(new AssignSoftware($request, $id));
		return redirect('software')->with('success-message', __('tasks.success'));
	}

	/**
	 * Unassign a software from the room
	 * 
	 * @param  Request $request 
	 * @param  int  $id      
	 * @return 
	 */
	public function unassign(Request $request, $id)
	{
		$this->dispatch(new UnassignSoftware($request, $id));
		return redirect('software')->with('success-message', __('tasks.success'));
	}
}

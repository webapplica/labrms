<?php

namespace App\Http\Controllers\Inventory\Workstation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WorkstationController extends Controller 
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if($request->ajax()) {
			$workstations = Workstation::all();
			return datatables($workstations)->toJson();
		}

		return view('workstation.index');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		return view('workstation.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->dispatch(new AssembleWorkstation($request));	
		return redirect('workstation')->with('success-message', __('tasks.success'));
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $id)
	{

		$workstation = Workstation::findOrFail($id);
		if($request->ajax()) {
			return datatables($workstation->tickets)->toJson();
		}

		// $workstation = App\Workstation::find($id);

		// if( App\Workstation::where('id', '=', $id)->count() <= 0 ) return view('errors.404');

		// $room = $workstation->systemunit->pluck('location')->first();
		// $software = App\Software::whereHas('rooms', function($query) use ($room) {
		// 				$query->where('room_id','=', $room);
		// 			})->get();

		// $mouse_issued = App\Ticket::whereIn('id', function($query) use ($id)
		// {
		// 	$query->where('workstation_id','=',$id)
		// 		->from('workstation_ticket')
		// 		->select('ticket_id');
		// })->where('details', 'like', "%As Mouse Brand%")->count();

		// $ticket_type = App\TicketType::firstOrCreate([ 'name' => 'Receive' ]);
		// $total = App\Ticket::whereIn('id',function($query) use ($id)
		// {
		// 	$query->where('workstation_id','=',$id)
		// 		->from('workstation_ticket')
		// 		->select('ticket_id')
		// 		->pluck('ticket_id');
		// })->where('type_id','=', $ticket_type )->count();

		return view('workstation.show');
			// ->with('workstation',$workstation)
			// ->with('software',$software)
			// ->with('total_tickets',$total)
			// ->with('mouseissued',$mouse_issued);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $request, $id)
	{
		$workstation = Workstation::findOrFail($id);
		return view('workstation.edit');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{

		Session::flash('success-message','Workstation  updated');
		return redirect('workstation')->with('success-message', __('tasks.success'));
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		$this->dispatch(new DisassembleWorkstation($request, $id));
		return redirect('workstation')->with('success-message', __('tasks.success'));
	}

	/**
	*
	*	function for deploying workstation to another location
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
	*	function for transfering workstation to another location
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

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use Auth;
use DB;
use App;
use Illuminate\Http\Request;

class WorkstationController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if($request->ajax())
		{
			$workstations = App\Workstation::all();
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
		$systemunit = $this->sanitizeString($request->get('systemunit'));
		$monitor = $this->sanitizeString($request->get('monitor'));
		$avr = $this->sanitizeString($request->get('avr'));
		$keyboard = $this->sanitizeString($request->get('keyboard'));
		$oskey = $this->sanitizeString($request->get('os'));
		$mouse = $this->sanitizeString($request->get('mouse'));
		$name = $this->sanitizeString($request->get('name'));

		$validator = Validator::make([
			'License Key' => $oskey,
			'AVR' => $avr,
			'Keyboard' => $keyboard,
			'Monitor' => $monitor,
			'System Unit' => $systemunit,
			'Mouse' => $mouse
		],App\Workstation::$rules);

		if($validator->fails())
		{
			return redirect('workstation/create')
					->withInput()
					->withErrors($validator);
		}

		/*
		*
		*	Transaction used to prevent error on saving
		*
		*/
		DB::beginTransaction();

		$systemunit = App\Item::findByPropertyNumber($systemunit)->pluck('id')->first();

		if($monitor != "")
			$monitor = App\Item::findByPropertyNumber($monitor)->first();
		else
			$monitor = null;

		if($keyboard != "")
			$keyboard = App\Item::findByPropertyNumber($keyboard)->first();
		else
			$keyboard = null;

		if($avr != "")
			$avr = App\Item::findByPropertyNumber($avr)->first();
		else
			$avr = null;

		if($mouse != "")
			$mouse = App\Item::findByLocalId($mouse)->first();
		else
			$mouse = null;

		$workstation = new App\Workstation;
		$workstation->systemunit_id = $systemunit;
		$workstation->monitor_id = $monitor;
		$workstation->avr_id = $avr;
		$workstation->keyboard_id = $keyboard;
		$workstation->oskey = $oskey;
		$workstation->mouse_id = $mouse;
		$workstation->assemble();

		DB::commit();
		
		Session::flash('success-message','Workstation assembled');
		return redirect('workstation');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $id)
	{

		if($request->ajax())
		{
			$workstation = App\Workstation::find($id);
			return datatables($workstation->tickets)->toJson();
		}

		$workstation = App\Workstation::find($id);

		if( App\Workstation::where('id', '=', $id)->count() <= 0 ) return view('errors.404');

		$room = $workstation->systemunit->pluck('location')->first();
		$software = App\Software::whereHas('rooms', function($query) use ($room) {
						$query->where('room_id','=', $room);
					})->get();

		$mouse_issued = App\Ticket::whereIn('id', function($query) use ($id)
		{
			$query->where('workstation_id','=',$id)
				->from('workstation_ticket')
				->select('ticket_id');
		})->where('details', 'like', "%As Mouse Brand%")->count();

		$ticket_type = App\TicketType::firstOrCreate([ 'name' => 'Receive' ]);
		$total = App\Ticket::whereIn('id',function($query) use ($id)
		{
			$query->where('workstation_id','=',$id)
				->from('workstation_ticket')
				->select('ticket_id')
				->pluck('ticket_id');
		})->where('type_id','=', $ticket_type )->count();

		return view('workstation.show')
			->with('workstation',$workstation)
			->with('software',$software)
			->with('total_tickets',$total)
			->with('mouseissued',$mouse_issued);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $request, $id)
	{
		$workstation = App\Workstation::find($id);

		return view('workstation.edit')
			->with('workstation',$workstation);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$avr = $this->sanitizeString($request->get('avr'));
		$monitor = $this->sanitizeString($request->get('monitor'));
		$os = $this->sanitizeString($request->get('os'));
		$keyboard = $this->sanitizeString($request->get('keyboard'));
		$mouse = $this->sanitizeString($request->get('mouse'));
		$systemunit = $this->sanitizeString($request->get('systemunit'));

		$validator = Validator::make([
		  'Operating System Key' => $os,
		  'System Unit' => $systemunit,
		  'AVR' => $avr,
		  'Keyboard' => $keyboard,
		  'Mouse' => $mouse,
		  'Monitor' => $monitor
		],App\Workstation::$updateRules);

		if($validator->fails())
		{
		  return redirect("workstation/$id/edit")
		    ->withInput()
		    ->withErrors($validator);
		}

		/*
		*
		*	Transaction used to prevent error on saving
		*
		*/
		DB::beginTransaction();

		$workstation = App\Workstation::find($id);
		$workstation->oskey = $os;
		$workstation->mouse_id = $mouse;
		$workstation->monitor_id = $monitor;
		$workstation->avr_id = $avr;
		$workstation->keyboard_id = $keyboard;
		$workstation->systemunit_id = $systemunit;

		$details = "Workstation updated with the following propertynumber:" ;
		$details = $details . "$_avr->propertynumber for AVR";
		$details = $details . "$_monitor->propertynumber for Monitor ";
		$details = $details . "$_keyboard->propertynumber for Keyboard";
		$details = $details .  "$mouse as mouse brand";

		$workstation->updateParts();
		
		DB::commit();

		Session::flash('success-message','Workstation  updated');
		return redirect('workstation');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		if($request->ajax())
		{
			$workstation = $this->sanitizeString($request->get('selected'));
			$keyboard = $this->sanitizeString($request->get('keyboard'));
			$avr = $this->sanitizeString($request->get('avr'));
			$monitor = $this->sanitizeString($request->get('monitor'));
			$systemunit = $this->sanitizeString($request->get('systemunit'));
			try
			{
				App\Workstation::condemn($workstation,$systemunit,$monitor,$keyboard,$avr);
			} 
			catch ( Exception $e ) 
			{  
				return json_encode('error');
			}

			return json_encode('success');
		}

		$workstation = $this->sanitizeString($request->get('selected'));
		App\Workstation::condemn($workstation,$systemunit,$monitor,$keyboard,$avr);

		Session::flash('success-message','Workstation condemned');
		return redirect('workstation');
	}

	/**
	*
	*	function for deploying workstation to another location
	*	@param $room accepts room name
	*	@param $workstation accepts workstation id list
	*
	*/
	public function deploy(Request $request)
	{

		DB::beginTransaction();

		$room = $this->sanitizeString($request->get('room'));
		$workstation = $this->sanitizeString($request->get('items'));
		$name = $this->sanitizeString($request->get('name'));

		App\Workstation::setWorkstationLocation($workstation,$room);
		$workstation = App\Workstation::find($workstation);
		$workstation->name = $name;
		$workstation->save();

		DB::commit();

		/**
		*
		*	check if the request is ajax
		*
		*/
		if($request->ajax())
		{
			return json_encode('success');
		}

		Session::flash('success-message','Workstation deployed');
		return redirect('workstation/form/deployment');
	}

	/**
	*
	*	function for transfering workstation to another location
	*	@param $room accepts room name
	*	@param $workstation accepts workstation id list
	*
	*/
	public function transfer(Request $request)
	{

		/**
		*
		*	check if the request is ajax
		*
		*/
		if($request->ajax())
		{
			$room = $this->sanitizeString($request->get('room'));
			$workstation = $this->sanitizeString($request->get('items'));
			$name = $this->sanitizeString($request->get('name'));

			App\Workstation::setWorkstationLocation($workstation,$room);
			$workstation = App\Workstation::find($workstation);
			$workstation->name = $name;
			$workstation->save();

			return json_encode('success');
		}

		/**
		*
		*	normal request
		*
		*/
		$room = $this->sanitizeString($request->get('room'));
		$workstation = $this->sanitizeString($request->get('items'));
		$name = $this->sanitizeString($request->get('name'));

		App\Workstation::setWorkstationLocation($workstation,$room);
		$workstation = App\Workstation::find($workstation);
		$workstation->name = $name;
		$workstation->save();

		Session::flash('success-message','Workstation transferred');
		return redirect('workstation/view/transfer');
	}

	/**
	*
	*	@param $id requires pc id
	*	@return list of pc ticket
	*
	*/
	public function getWorkstationTicket(Request $request, $id)
	{
		$ticket = new App\Ticket;
		$ticket->getPcTickets($id);
		if($request->ajax())
		{

			return json_encode([
				'data' => $ticket
			]);
		}

		return json_encode([
			'data' => $ticket
		]);
	}

}
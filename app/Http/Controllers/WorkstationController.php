<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use Auth;
use DB;
use App;
use App\Pc;
use App\Software;
use App\Supply;
use App\Ticket;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class WorkstationController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if(Request::ajax())
		{
			return json_encode(['data'=> Pc::with('keyboard','avr','monitor','systemunit.roominventory.room')->get()]);
		}

		return view('workstation.index');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('workstation.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$systemunit = $this->sanitizeString(Input::get('systemunit'));
		$monitor = $this->sanitizeString(Input::get('monitor'));
		$avr = $this->sanitizeString(Input::get('avr'));
		$keyboard = $this->sanitizeString(Input::get('keyboard'));
		$oskey = $this->sanitizeString(Input::get('os'));
		$mouse = $this->sanitizeString(Input::get('mouse'));
		$name = $this->sanitizeString(Input::get('name'));

		$validator = Validator::make([
			'Operating System Key' => $oskey,
			'avr' => $avr,
			'Keyboard' => $keyboard,
			'Monitor' => $monitor,
			'System Unit' => $systemunit,
			'Mouse' => $mouse
		],Pc::$rules);

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


		$pc = new App\Pc;
		$pc->systemunit_id = $systemunit;
		$pc->monitor_id = ($monitor == "" || is_null($monitor)) ? null : $monitor;
		$pc->avr_id = ($avr == "" || is_null($avr)) ? null : $avr;
		$pc->keyboard_id = ($keyboard == "" || is_null($keyboard)) ? null : $keyboard;
		$pc->oskey = $oskey;
		$pc->mouse_id = ($mouse == "" || is_null($mouse)) ? null : $mouse;
		$pc->assemble();

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
	public function show($id)
	{

		if(Request::ajax())
		{
			$workstation = Pc::find($id);

			return json_encode([
				'data' => App\Software::whereHas('roomsoftware',function($query) use ($workstation) {
								$query->where('room_id','=',$workstation->systemunit->roominventory->room_id);
							})
							->with('pcsoftware.softwarelicense')
							->get()
			]);
		}

		try{

			$room = "";
			$software = "";
			$workstation = App\Pc::with('systemunit')
						->with('keyboard')
						->with('monitor')
						->find($id);

			if($workstation)
			{
				$room = $workstation->systemunit->roominventory->room_id;

				try
				{
					$software = App\Software::whereHas('roomsoftware',function($query) use ($room) {
								$query->where('room_id','=',$room);
							})->get();
				} 
				catch (Exception $e) 
				{ 
					$software = '';
				}
			}

			$total = 0;
			$mouseissued = 0;

			$mouseissued = App\Ticket::whereIn('id',function($query) use ($id)
			{

				/*
				|--------------------------------------------------------------------------
				|
				| 	checks if pc is in ticket
				|
				|--------------------------------------------------------------------------
				|
				*/
				$query->where('pc_id','=',$id)
					->from('pc_ticket')
					->select('ticket_id')
					->pluck('ticket_id');
			})->where('details','like','%'.'As Mouse Brand' . '%')->count();

			$total = App\Ticket::whereIn('id',function($query) use ($id)
			{

				/*
				|--------------------------------------------------------------------------
				|
				| 	checks if pc is in ticket
				|
				|--------------------------------------------------------------------------
				|
				*/
				$query->where('pc_id','=',$id)
					->from('pc_ticket')
					->select('ticket_id')
					->pluck('ticket_id');
			})->where('tickettype','=','Complaint')->count();

			return view('workstation.show')
				->with('workstation',$workstation)
				->with('software',$software)
				->with('total_tickets',$total)
				->with('mouseissued',$mouseissued);
		} 

		catch (Exception $e) 
		{
			return redirect('workstation');
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$pc = Pc::where('id','=',$id)
					->with('keyboard','avr','monitor','systemunit.roominventory.room')
					->first();

		return view('workstation.edit')
			->with('pc',$pc);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$avr = $this->sanitizeString(Input::get('avr'));
		$monitor = $this->sanitizeString(Input::get('monitor'));
		$os = $this->sanitizeString(Input::get('os'));
		$keyboard = $this->sanitizeString(Input::get('keyboard'));
		$mouse = $this->sanitizeString(Input::get('mouse'));
		$systemunit = $this->sanitizeString(Input::get('systemunit'));

		$validator = Validator::make([
		  'Operating System Key' => $os,
		  'System Unit' => $systemunit,
		  'AVR' => $avr,
		  'Keyboard' => $keyboard,
		  'Mouse' => $mouse,
		  'Monitor' => $monitor
		],Pc::$updateRules);

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

		$pc = Pc::find($id);
		$pc->oskey = $os;
		$pc->mouse_id = $mouse;
		$pc->monitor_id = $monitor;
		$pc->avr_id = $avr;
		$pc->keyboard_id = $keyboard;
		$pc->systemunit_id = $systemunit;

		$details = "Workstation updated with the following propertynumber:" ;
		$details = $details . "$_avr->propertynumber for AVR";
		$details = $details . "$_monitor->propertynumber for Monitor ";
		$details = $details . "$_keyboard->propertynumber for Keyboard";
		$details = $details .  "$mouse as mouse brand";

		$pc->updateParts();
		
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
	public function destroy($id)
	{
		if(Request::ajax())
		{
			$pc = $this->sanitizeString(Input::get('selected'));
			$keyboard = $this->sanitizeString(Input::get('keyboard'));
			$avr = $this->sanitizeString(Input::get('avr'));
			$monitor = $this->sanitizeString(Input::get('monitor'));
			$systemunit = $this->sanitizeString(Input::get('systemunit'));
			try
			{
				Pc::condemn($pc,$systemunit,$monitor,$keyboard,$avr);
			} 
			catch ( Exception $e ) 
			{  
				return json_encode('error');
			}

			return json_encode('success');
		}

		$pc = $this->sanitizeString(Input::get('selected'));
		Pc::condemn($pc,$systemunit,$monitor,$keyboard,$avr);

		Session::flash('success-message','Workstation condemned');
		return redirect('workstation');
	}

	/**
	*
	*	function for deploying pc to another location
	*	@param $room accepts room name
	*	@param $pc accepts pc id list
	*
	*/
	public function deploy()
	{

		/**
		*
		*	check if the request is ajax
		*
		*/
		if(Request::ajax())
		{
			$room = $this->sanitizeString(Input::get('room'));
			$pc = $this->sanitizeString(Input::get('items'));
			$name = $this->sanitizeString(Input::get('name'));

			Pc::setPcLocation($pc,$room);
			$pc = Pc::find($pc);
			$pc->name = $name;
			$pc->save();

			return json_encode('success');
		}

		/**
		*
		*	normal request
		*
		*/
		$room = $this->sanitizeString(Input::get('room'));
		$pc = $this->sanitizeString(Input::get('items'));
		$name = $this->sanitizeString(Input::get('name'));

		Pc::setPcLocation($pc,$room);
		$pc = Pc::find($pc);
		$pc->name = $name;
		$pc->save();

		Session::flash('success-message','Workstation deployed');
		return redirect('workstation/form/deployment');
	}

	/**
	*
	*	function for transfering pc to another location
	*	@param $room accepts room name
	*	@param $pc accepts pc id list
	*
	*/
	public function transfer()
	{

		/**
		*
		*	check if the request is ajax
		*
		*/
		if(Request::ajax())
		{
			$room = $this->sanitizeString(Input::get('room'));
			$pc = $this->sanitizeString(Input::get('items'));
			$name = $this->sanitizeString(Input::get('name'));

			Pc::setPcLocation($pc,$room);
			$pc = Pc::find($pc);
			$pc->name = $name;
			$pc->save();

			return json_encode('success');
		}

		/**
		*
		*	normal request
		*
		*/
		$room = $this->sanitizeString(Input::get('room'));
		$pc = $this->sanitizeString(Input::get('items'));
		$name = $this->sanitizeString(Input::get('name'));

		Pc::setPcLocation($pc,$room);
		$pc = Pc::find($pc);
		$pc->name = $name;
		$pc->save();

		Session::flash('success-message','Workstation transferred');
		return redirect('workstation/view/transfer');
	}

}

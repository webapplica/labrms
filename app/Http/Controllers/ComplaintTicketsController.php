<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon;
use Session;
use Validator;
use Auth;
use App;
use App\TicketView;
use App\Ticket;
use App\PcTicket;
use App\RoomTicket;
use App\Room;
use App\ItemProfile;
use App\MaintenanceActivity;
use App\Pc;
use DB;
use App\User;
use App\TicketType;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class TicketsController extends Controller {

	private $assigned = "";
	private $author = "";
	private $comments = "";
	private $details = "";
	private $id = "";
	private $staff_id = null;
	private $staffassigned = null;
	private $status = "Open";
	private $type = "";
	private $ticketname = "";
	private $tickettype = "";
	private $ticket_id = "";

	private $ticket_status = [
		'Open',
		'Closed'
	];

	function __construct()
	{
		$this->author = Auth::user()->firstname . " " . Auth::user()->middlename . " " .Auth::user()->lastname;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		/*
		|--------------------------------------------------------------------------
		|
		| 	Check if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Request::ajax())
		{

			/*
			|--------------------------------------------------------------------------
			|
			| 	Laboratory Staff
			|
			|--------------------------------------------------------------------------
			|
			*/
			$query = TicketView::orderBy('date','desc');
			if( Auth::user()->accesslevel == 2 )
			{
				$query = $query->staff(Auth::user()->id);
			}

			if(Input::has('type'))
			{
				$query = $query->tickettype($this->sanitizeString(Input::get('type')));
			}

			if(Input::has('assigned'))
			{
				$assigned = $this->sanitizeString(Input::get('assigned'));
			}

			if(Input::has('status'))
			{
				$query = $query->status(Input::get('status'));
			}

			/*
			|--------------------------------------------------------------------------
			|
			| 	Laboratory Users
			|
			|--------------------------------------------------------------------------
			|
			*/
			if( Auth::user()->accesslevel == 3 || Auth::user()->accesslevel == 4  )
			{
				$query = $query->self()->tickettype('Complaint');
			}

			return json_encode([
				'data' => $query->get()
		 	]);
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	Total Ticket Count
		|
		|--------------------------------------------------------------------------
		|
		*/
		$total_tickets = App\Ticket::count();
		$complaints = App\Ticket::tickettype('complaint')
						->open()
						->count();

		$author = Auth::user()->firstname." ".Auth::user()->middlename." ".Auth::user()->lastname;
		$authored_tickets = App\Ticket::where('author','=',$author)
																->count();
		$open_tickets = App\Ticket::tickettype('complaint')
															->open()
															->count();

		$ticket_type = App\TicketType::all();

		return view('ticket.index')
				->with('tickettype',$ticket_type)
				->with('ticketstatus',$this->ticket_status)
				->with('lastticket',$total_tickets + 1)
				->with('total_tickets',$total_tickets)
				->with('complaints',$complaints)
				->with('authored_tickets',$authored_tickets)
				->with('open_tickets',$open_tickets);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$last_generated_ticket = App\Ticket::count() + 1;

		$staff = User::staff()
						->whereNotIn('id',[ Auth::user()->id, User::admin()->first()->id ])
						->select(
							'id as id',
							DB::raw('CONCAT( firstname , " " , middlename , " " , lastname ) as name')
						)
						->pluck('name','id');

		return view('ticket.create')
				->with('lastticket',$last_generated_ticket)
				->with('staff',$staff);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$tag = $this->sanitizeString(Input::get('tag'));
		$this->tickettype = 'complaint';

		if(Input::has('tickettype'))
		{
			$this->tickettype = 'incident';
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	Verifies if the user inputs  a title
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Input::has('subject'))
		{
			$this->ticketname = $this->sanitizeString(Input::get('subject'));

			/*
			|--------------------------------------------------------------------------
			|
			| 	Check if ticketname has no value
			|	if no value, type will be automatically complaint
			|
			|--------------------------------------------------------------------------
			|
			*/
			if($this->ticketname == '' || $this->ticketname == null)
			{
				$this->ticketname = $tickettype;
			}
		}
		else
		{
			$this->ticketname = $tickettype;
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	Verifies if the user inputs an author
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Input::has('author'))
		{
			$this->author = $this->sanitizeString(Input::get('author'));
		}

		$details = $this->sanitizeString(Input::get('description'));

		/*
		|--------------------------------------------------------------------------
		|
		| 	Verifies if the user inputs an author
		|
		|--------------------------------------------------------------------------
		|
		*/
		$user = User::where('accesslevel','=',0)->first();

		if(Auth::user()->accesslevel == 2)
		{
			if(Input::has('assign-to-staff'))
			{
				$this->staffassigned = $this->sanitizeString(Input::get('staffassigned'));
			}
			else
			{
				$this->staffassigned = Auth::user()->id;
			}
		}

		$validator = Validator::make([
				'Ticket Subject' => $this->ticketname,
				'Details' => $this->details,
				'Author' => $this->author,
			],Ticket::$complaintRules);

		if($validator->fails())
		{
			return redirect('ticket/create')
				->withInput()
				->withErrors($validator);
		}

		$this->generateTaggedTicket($tag);

		Session::flash('success-message','Ticket Generated');
		return redirect('ticket');

	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{

	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$ticket = Ticket::find($id);
		return view('ticket.edit')
				->with('ticket',$ticket);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$propertynumber = $this->sanitizeString(Input::get('propertynumber'));
		$type = $this->sanitizeString(Input::get('type'));
		$maintenancetype = $this->sanitizeString(Input::get('maintenancetype'));
		$category = $this->sanitizeString(Input::get('category'));
		$author = $this->sanitizeString(Input::get('author'));
		$details = $this->sanitizeString(Input::get('description'));
		$staffassigned = Auth::user()->id;
		$propertynumber;

		$ticket = Ticket::find($id);
		$ticket->item_id = $propertynumber;
		$ticket->ticketname = $category;
		$ticket->tickettype = $type;
		$ticket->details = $maintenancetype . $details;
		$ticket->author = $author;
		$ticket->save();

		Session::flash('success-message','Ticket Generated');
		return redirect('ticket');

	}

	/**
	 * Transfer ticket to another user.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function transfer($id = null)
	{
		/*
		|--------------------------------------------------------------------------
		|
		| 	Initialize
		|
		|--------------------------------------------------------------------------
		|
		*/
		$this->id = $this->sanitizeString(Input::get('id'));
		$this->staffassigned = $this->sanitizeString(Input::get('transferto'));
		$this->comments = $this->sanitizeString(Input::get('comment'));

		/*
		|--------------------------------------------------------------------------
		|
		| 	Validation
		|
		|--------------------------------------------------------------------------
		|
		*/
		$validator = Validator::make([
				'Ticket ID' => $this->id,
				'Staff Assigned' => $this->staffassigned
			],Ticket::$transferRules);

		if($validator->fails())
		{
			Session::flash('error-message','Problem encountered while processing your request');
			return redirect()->back();
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	Transfer....
		|
		|--------------------------------------------------------------------------
		|
		*/
		$this->transferTicket();

		Session::flash('success-message','Ticket Transferred');
		return redirect()->back();
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
			Ticket::closeTicket($id);
			return json_encode('success');
		}
	}

	/**
	 * Restore the specified resource
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function reOpenTicket($id)
	{
		if(Request::ajax())
		{
			Ticket::reOpenTicket($id);
			return json_encode('success');
		}
	}

	public function showHistory($id)
	{
		if(Request::ajax())
		{
			$arraylist = array();
			$cond = true;
			$start = 0;
			$ticket;
			do
			{

				/*
				|--------------------------------------------------------------------------
				|
				| 	$start = 0	->	original
				|	$start = 1	->	next ticket
				|	$start = 2	->	last
				|
				|--------------------------------------------------------------------------
				|
				*/
				if($start == 0)
				{

					/*
					|--------------------------------------------------------------------------
					|
					| 	Get all the previous ticket
					|
					|--------------------------------------------------------------------------
					|
					*/
					$ticket =  Ticket::where('ticket_id','=',$id)
								->orderBy('id','desc')
								->with('user')
								->whereNotIn('id',array_pluck($arraylist,'id'))
								->first();
				}
				else
				{

					/*
					|--------------------------------------------------------------------------
					|
					| 	Get all the ticket connected to the original
 					|
					|--------------------------------------------------------------------------
					|
					*/
					$ticket =  Ticket::where('id','=',$id)
								->orderBy('id','desc')
								->whereNotIn('id',array_pluck($arraylist,'id'))
								->with('user')
								->first();
				}

				try
				{

					/*
					|--------------------------------------------------------------------------
					|
					| 	Ticket exists
					|
					|--------------------------------------------------------------------------
					|
					*/
					if(isset($ticket))
					{

						/*
						|--------------------------------------------------------------------------
						|
						| 	If original
						|
						|--------------------------------------------------------------------------
						|
						*/
						if($start == 1)
						{
							$id = $ticket->ticket_id;
						}

						array_push($arraylist,$ticket);
					}
					else
					{
						if($start == 2)
						{

							/*
							|--------------------------------------------------------------------------
							|
							| 	all connected ticket are used
							|
							|--------------------------------------------------------------------------
							|
							*/
							$cond  = false;
						}
						else if($start == 1)
						{

							/*
							|--------------------------------------------------------------------------
							|
							| 	no more previous ticket
							|
							|--------------------------------------------------------------------------
							|
							*/
							$start = 2;
						}
						else
						{
							$start = 1;
						}
					}
				}
				catch( Exception $e )
				{
					$cond = false;
				}


			} while ( $cond == true);

			return json_encode([ 'data'=> $arraylist ]);
		}

		try
		{

			$ticket = TicketView::where('id','=',$id)
								->first();

			$lastticket = Ticket::orderBy('created_at', 'desc')->first();

			if (count($lastticket) == 0 )
			{
				$lastticket = 1;
			}
			else if ( count($lastticket) > 0 )
			{
				$lastticket = $lastticket->id + 1;
			}

			if(!isset($ticket) || count($ticket) <= 0)
			{
				return redirect('ticket');
			}

			return view('ticket.show')
				->with('ticket',$ticket)
				->with('id',$id)
				->with('lastticket',$lastticket);
		}
		catch ( Exception $e )
		{

			Session::flash('error-message','Problem encountered while processing your request');
			return redirect('ticket');

		}
	}

	/**
	*
	*	@return ajax: 'success' or 'error'
	*	normal: view with prompt
	*
	*
	*/
	public function resolve()
	{
		/*
		|--------------------------------------------------------------------------
		|
		| 	Intantiate Values
		|
		|--------------------------------------------------------------------------
		|
		*/
		$details = "";
		$id = $this->sanitizeString(Input::get('id'));
		$status = 'Open';
		$underrepair = false;

		if(Input::has('contains'))
		{
			$details = $this->sanitizeString(Input::get('details'));
		} else
		{

			/*
			|--------------------------------------------------------------------------
			|
			| 	use maintenance activity
			|
			|--------------------------------------------------------------------------
			|
			*/
			try
			{
				/*
				|--------------------------------------------------------------------------
				|
				| 	get the activity field
				|
				|--------------------------------------------------------------------------
				|
				*/
				$activity = $this->sanitizeString(Input::get('activity'));
				$maintenanceactivity = MaintenanceActivity::find($activity);
				$details = $maintenanceactivity->activity;
			} catch (Exception $e) {}
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	check if the status will be changed
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Input::has('underrepair'))
		{
			$underrepair = 'underrepair';
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	check if the status will be changed
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Input::has('working'))
		{
			$underrepair = 'working';
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	check if the the ticket will be closed
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Input::has('close'))
		{
			$status = "Closed";
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	validates
		|
		|--------------------------------------------------------------------------
		|
		*/
		$validator = Validator::make([
				'Details' => $details
		],Ticket::$maintenanceRules);

		if($validator->fails())
		{
			Session::flash('error-message','Process run into an error');
			return redirect()->back();
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	call function resolve ticket
		|
		|--------------------------------------------------------------------------
		|
		*/
		Ticket::resolveTicket($id,$details,$status,$underrepair);

		/*
		|--------------------------------------------------------------------------
		|
		| 	return successful
		|
		|--------------------------------------------------------------------------
		|
		*/
		Session::flash('success-message','Action Created');
		return redirect()->back();
	}

	/**
	*
	*	complain process
	*
	*/
	public function complaint()
	{
		return redirect('ticket/complaint');
	}

	/**
	*
	*	@return complaint view
	*	@return opened ticket
	*
	*/
	public function complaintViewForStudentAndFaculty()
	{
		if(Request::ajax())
		{
			return json_encode([
					'data' => Ticket::with('itemprofile')
										->with('user')
										->where('status','=','Open')
										->get()
				]);
		}
		return view('ticket.complaint');
	}

	/**
	*
	*	@param $id requires pc id
	*	@return list of pc ticket
	*
	*/
	public function getPcTicket($id)
	{
		if(Request::ajax())
		{

			/*
			|--------------------------------------------------------------------------
			|
			| 	get pc id
			|
			|--------------------------------------------------------------------------
			|
			*/
			$pc = PcTicket::where('pc_id','=',$id)->pluck('id');

			/*
			|--------------------------------------------------------------------------
			|
			| 	return ticket with pc information
			|
			|--------------------------------------------------------------------------
			|
			*/
			return json_encode(
			[
				'data' => Ticket::whereIn('id',function($query) use ($id)
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
				})->get()
			]);
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	get pc id
		|
		|--------------------------------------------------------------------------
		|
		*/
		$pc = PcTicket::where('pc_id','=',$id)->pluck('id');

		/*
		|--------------------------------------------------------------------------
		|
		| 	return ticket with pc information
		|
		|--------------------------------------------------------------------------
		|
		*/
		return json_encode(
		[
			'data' => Ticket::whereIn('id',function($query) use ($id)
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
			})->get()
		]);
	}

	/**
	*
	*	@param $id requires pc id
	*	@return list of room ticket
	*
	*/
	public function getRoomTicket($id)
	{
		if(Request::ajax())
		{

			/*
			|--------------------------------------------------------------------------
			|
			| 	get room id
			|
			|--------------------------------------------------------------------------
			|
			*/
			$pc = RoomTicket::where('room_id','=',$id)->pluck('id');

			/*
			|--------------------------------------------------------------------------
			|
			| 	return ticket with room information
			|
			|--------------------------------------------------------------------------
			|
			*/
			return json_encode(
			[
				'data' => Ticket::whereIn('id',function($query) use ($id)
				{

					/*
					|--------------------------------------------------------------------------
					|
					| 	checks if room is in ticket
					|
					|--------------------------------------------------------------------------
					|
					*/
					$query->where('room_id','=',$id)
						->from('room_ticket')
						->select('ticket_id')
						->pluck('ticket_id');
				})->get()
			]);
		}
	}

	/**
	*
	*	@param $tag
	*	@return item information
	*	@return is existing room
	*	@return pc information
	*
	*/
	public function getTagInformation()
	{

		/*
		|--------------------------------------------------------------------------
		|
		| 	uses ajax request
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Request::ajax())
		{
			$tag = $this->sanitizeString(Input::get('id'));

			/*
			|--------------------------------------------------------------------------
			|
			| 	Check if the equipment is connected to pc
			|
			|--------------------------------------------------------------------------
			|
			*/
			$pc = Pc::isPc($tag);
			if(count($pc) > 0)
			{
				$pc = Pc::with('systemunit')->with('monitor')->with('keyboard')->with('avr')->find($pc->id);
				return json_encode($pc);
			}

			/*
			|--------------------------------------------------------------------------
			|
			| 	Check if the tag is equipment
			|
			|--------------------------------------------------------------------------
			|
			*/
			$itemprofile = ItemProfile::propertyNumber($tag)->first();
			if( count($itemprofile) > 0)
			{
				/*
				|--------------------------------------------------------------------------
				|
				| 	Create equipment ticket
				|
				|--------------------------------------------------------------------------
				|
				*/
				return json_encode($itemprofile);

			}

			/*
			|--------------------------------------------------------------------------
			|
			| 	Check if the tag is room
			|
			|--------------------------------------------------------------------------
			|
			*/
			$room = Room::location($tag)->first();
			if( count($room) > 0 )
			{
				return json_encode($room);
			}

			/*
			|--------------------------------------------------------------------------
			|
			| 	return false if no item found
			|
			|--------------------------------------------------------------------------
			|
			*/
			return json_encode('error');
		}

		$tag = $this->sanitizeString(Input::get('tag'));

		/*
		|--------------------------------------------------------------------------
		|
		| 	Check if the tag is equipment
		|
		|--------------------------------------------------------------------------
		|
		*/
		$itemprofile = ItemProfile::propertyNumber($tag)->first();
		if( count($itemprofile) > 0)
		{

			/*
			|--------------------------------------------------------------------------
			|
			| 	Check if the equipment is connected to pc
			|
			|--------------------------------------------------------------------------
			|
			*/
			$pc = Pc::isPc($tag);
			if(count($pc) > 0)
			{
				return $pc;
			}
			else
			{

				/*
				|--------------------------------------------------------------------------
				|
				| 	Create equipment ticket
				|
				|--------------------------------------------------------------------------
				|
				*/
				return $itemprofile;
			}

		}
		else
		{

			/*
			|--------------------------------------------------------------------------
			|
			| 	Check if the tag is room
			|
			|--------------------------------------------------------------------------
			|
			*/
			$room = Room::location($tag)->first();
			if( count($room) > 0 )
			{
				return $room;
			}
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	return false if no item found
		|
		|--------------------------------------------------------------------------
		|
		*/
		return json_encode('error');
	}

	/**
	*
	*	maintenance view
	*
	*/
	public function maintenanceView()
	{
		$ticket = Ticket::orderBy('created_at', 'desc')->first();
		$activity = MaintenanceActivity::pluck('activity','id');

		if (count($ticket) == 0 )
		{
			$ticket = 1;
		}
		else if ( count($ticket) > 0 )
		{
			$ticket = $ticket->id + 1;
		}

		if(count($activity) == 0)
		{
			$activity = [ 'None' => 'No suggestion available' ];
		}

		return view('ticket.maintenance')
				->with('lastticket',$ticket)
				->with('activity',$activity);
	}


	/**
	 * Maintenance function.
	 *
	 * @return Response
	 */
	public function maintenance()
	{
		/*
		|--------------------------------------------------------------------------
		|
		| 	init ...
		|
		|--------------------------------------------------------------------------
		|
		*/
		$tag = $this->sanitizeString(Input::get('tag'));
		$ticketname = "Maintenance Ticket";
		$underrepair = false;
		$workstation = false;
		$details = "";

		/*
		|--------------------------------------------------------------------------
		|
		| 	check if item is not in the field list
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Input::has('contains'))
		{
			$details = $this->sanitizeString(Input::get('description'));
		}
		else
		{

			/*
			|--------------------------------------------------------------------------
			|
			| 	use maintenance activity
			|
			|--------------------------------------------------------------------------
			|
			*/
			try
			{
				/*
				|--------------------------------------------------------------------------
				|
				| 	get the activity field
				|
				|--------------------------------------------------------------------------
				|
				*/
				$activity = $this->sanitizeString(Input::get('activity'));
				$maintenanceactivity = MaintenanceActivity::find($activity);
				$ticketname = $maintenanceactivity->activity;

				if(isset($maintenanceactivity->details) && $maintenanceactivity->details != "")
				{
					$details =  $maintenanceactivity->details;
				}
				else
				{
					$details = "No specified details";
				}

			} catch (Exception $e) {}
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	check if item will be set to underrepair
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Input::has('underrepair'))
		{
			$underrepair = true;
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	validates
		|
		|--------------------------------------------------------------------------
		|
		*/
		$validator = Validator::make([
				'Details' => $details
		],Ticket::$maintenanceRules);

		if($validator->fails())
		{
			return redirect('ticket/maintenance')
				->withInput()
				->withErrors($validator);
		}

		DB::beginTransaction();

		$tickettype = 'Maintenance';
		$author = Auth::user()->firstname . ' ' . Auth::user()->middlename . ' ' . Auth::user()->lastname;
		$staffassigned = Auth::user()->id;
		$status = 'Closed';
		$item = Input::get('item');

		Ticket::generateMaintenanceTicket($tag,$ticketname,$details,$underrepair,$workstation);

		if(count($item) > 0)
		{

			foreach($item as $item)
			{

				/*
				|--------------------------------------------------------------------------
				|
				| 	Check if the tag is equipment
				|
				|--------------------------------------------------------------------------
				|
				*/
				$itemprofile = ItemProfile::propertyNumber($item)->first();
				if( count($itemprofile) > 0)
				{

					/*
					|--------------------------------------------------------------------------
					|
					| 	Create equipment ticket
					|
					|--------------------------------------------------------------------------
					|
					*/
					Ticket::generateEquipmentTicket($itemprofile->id,$tickettype,$ticketname,$details,$author,$staffassigned,null,$status);

				}
				else
				{
					/*
					|--------------------------------------------------------------------------
					|
					| 	Check if the equipment is connected to pc
					|
					|--------------------------------------------------------------------------
					|
					*/
					$pc = Pc::isPc($item);
					if(count($pc) > 0)
					{
						Ticket::generatePcTicket($pc->id,$tickettype,$ticketname,$details,$author,$staffassigned,null,$status);
					}
				}
			}

		}

		DB::commit();

		Session::flash('success-message','Ticket Generated');
		return redirect('ticket');

	}

	public function generateTaggedTicket($tag)
	{

		/*
		|--------------------------------------------------------------------------
		|
		| 	Check if the tag is equipment
		|
		|--------------------------------------------------------------------------
		|
		*/
		$itemprofile = App\ItemProfile::propertyNumber($tag)->first();
		if( count($itemprofile) > 0)
		{

			/*
			|--------------------------------------------------------------------------
			|
			| 	Check if the equipment is connected to pc
			|
			|--------------------------------------------------------------------------
			|
			*/
			$pc = App\Pc::isPc($tag);
			if(count($pc) > 0)
			{
				$this->generatePcTicket($pc->id);
			}
			else
			{

				/*
				|--------------------------------------------------------------------------
				|
				| 	Create equipment ticket
				|
				|--------------------------------------------------------------------------
				|
				*/
				$this->generateEquipmentTicket($itemprofile->id);
			}

		}
		else
		{

			/*
			|--------------------------------------------------------------------------
			|
			| 	Check if the tag is room
			|
			|--------------------------------------------------------------------------
			|
			*/
			$room = App\Room::location($tag)->first();
			if( count($room) > 0 )
			{
				$this->generateRoomTicket($room->id);
			}
			else
			{
				/*
				|--------------------------------------------------------------------------
				|
				| 	Check if the equipment is connected to pc
				|
				|--------------------------------------------------------------------------
				|
				*/
				$pc = App\Pc::isPc($tag);
				if(count($pc) > 0)
				{
					$this->generatePcTicket($pc->id);
				}
				else
				{

					/*
					|--------------------------------------------------------------------------
					|
					| 	Create general ticket
					|
					|--------------------------------------------------------------------------
					|
					*/
					$this->generateTicket();
				}
			}
		}
	}

	function generatePcTicket()
	{
			DB::beginTransaction();

			/*
			|--------------------------------------------------------------------------
			|
			| 	Calls function generate from ticket table
			|	returns object
			|
			|--------------------------------------------------------------------------
			|
			*/
			$ticket = $this->generateTicket();

			/*
			|--------------------------------------------------------------------------
			|
			| 	Connects record from pc table to ticket table
			|
			|--------------------------------------------------------------------------
			|
			*/
			App\Pc::find($pc_id)->ticket()->attach($ticket->id);

			DB::commit();
	}

	function generateEquipmentTicket()
	{
		DB::beginTransaction();

		/*
		|--------------------------------------------------------------------------
		|
		| 	Calls function generate from ticket table
		|	returns object
		|
		|--------------------------------------------------------------------------
		|
		*/
		$ticket = $this->generateTicket();

		/*
		|--------------------------------------------------------------------------
		|
		| 	Connects record from item profile table to ticket table
		|
		|--------------------------------------------------------------------------
		|
		*/
		App\ItemProfile::find($item_id)->ticket()->attach($ticket->id);

		DB::commit();
	}

	function generateRoomTicket()
	{
		DB::beginTransaction();

		/*
		|--------------------------------------------------------------------------
		|
		| 	Calls function generate from ticket table
		|	returns object
		|
		|--------------------------------------------------------------------------
		|
		*/
		$ticket = $this->generateTicket();

		/*
		|--------------------------------------------------------------------------
		|
		| 	Connects record from room table to ticket table
		|
		|--------------------------------------------------------------------------
		|
		*/
		App\Room::find($room_id)->ticket()->attach($ticket->id);

		DB::commit();

	}

	public static function transferTicket($id)
	{

		/*
		|--------------------------------------------------------------------------
		|
		| 	call function close ticket
		|
		|--------------------------------------------------------------------------
		|
		*/
		$ticket = $this->updateTicketStatusToTransferred($id);

		/*
		|--------------------------------------------------------------------------
		|
		| 	call function generate ticket
		|
		|--------------------------------------------------------------------------
		|
		*/
		$this->tickettype = $ticket->tickettype;
		$this->ticketname = $ticket->ticketname;
		$this->details = $ticket->details;
		$this->author = $ticket->author;
		$this->ticket_id = $ticket->id;
		$this->status = 'Open';
		$ticket = $this->generateTicket();
	}

	function generateTicket()
	{
		$ticket = new App\Ticket;
		$ticket->tickettype = $this->tickettype;
		$ticket->ticketname = $this->ticketname;
		$ticket->details = $this->details;
		$ticket->author = $this->author;
		$ticket->staffassigned = $this->staffassigned;
		$ticket->ticket_id = $this->ticket_id;
		$ticket->status = $this->status;
		$ticket->comments = $this->comments;
		$ticket->save();
	}

}

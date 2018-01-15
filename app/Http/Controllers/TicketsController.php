<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon;
use Session;
use Validator;
use Auth;
use App;
use DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class TicketsController extends Controller {

	private $ticket_status = [
		'Open',
		'Closed'
	];

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
			$query = App\TicketView::orderBy('date','desc');
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

		$staff = App\User::staff()
						->whereNotIn('id',[ Auth::user()->id, App\User::admin()->first()->id ])
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
		$tickettype = 'complaint';
		$author = null;
		$staffassigned = null;

		if(Input::has('tickettype'))
		{
			$tickettype = 'incident';
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
			$ticketname = $this->sanitizeString(Input::get('subject'));

			/*
			|--------------------------------------------------------------------------
			|
			| 	Check if ticketname has no value
			|	if no value, type will be automatically complaint
			|
			|--------------------------------------------------------------------------
			|
			*/
			if($ticketname == '' || $ticketname == null)
			{
				$ticketname = $tickettype;
			}
		}
		else
		{
			$ticketname = $tickettype;
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
			$author = $this->sanitizeString(Input::get('author'));
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

		if(Auth::user()->accesslevel == 2)
		{
			if(Input::has('assign-to-staff'))
			{
				$staffassigned = $this->sanitizeString(Input::get('staffassigned'));
			}
		}

		$validator = Validator::make([
				'Ticket Subject' => $ticketname,
				'Details' => $details,
				'Author' => $author,
			],App\Ticket::$complaintRules);

		if($validator->fails())
		{
			return redirect('ticket/create')
				->withInput()
				->withErrors($validator);
		}

		$ticket = new App\Ticket;
		$ticket->ticketname = $ticketname;
		$ticket->tickettype = $tickettype;
		$ticket->details = $details;
		$ticket->author = $author;
		$ticket->staffassigned;
		$ticket->staffassigned = $staffassigned;
		$ticket->status = 'Open';
		$ticket->generate($tag);

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
		$ticket = App\Ticket::find($id);
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

		$ticket = App\Ticket::find($id);
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
		$id = $this->sanitizeString(Input::get('id'));
		$staffassigned = $this->sanitizeString(Input::get('transferto'));
		$comments = $this->sanitizeString(Input::get('comment'));

		/*
		|--------------------------------------------------------------------------
		|
		| 	Validation
		|
		|--------------------------------------------------------------------------
		|
		*/
		$validator = Validator::make([
				'Ticket ID' => $id,
				'Staff Assigned' => $staffassigned
			], App\Ticket::$transferRules );

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
		$ticket = App\Ticket::find($id);
		$ticket->status = 'Open';
		$ticket->comments = $comments;
		$ticket->staffassigned = $staffassigned;
		$ticket->transfer();

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
			$ticket = App\Ticket::find($id);

			if(count($ticket) <= 0) return json_encode('error');
			$ticket->close($id);
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
			$ticket = App\Ticket::find($id);

			if(count($ticket) > 0)
			{
				$ticket->reopen();
				return json_encode('success');
			}
			
			return json_encode('error');
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
					$ticket =  App\Ticket::where('ticket_id','=',$id)
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
					$ticket =  App\Ticket::where('id','=',$id)
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

			$ticket = App\TicketView::where('id','=',$id)
								->first();

			$lastticket = App\Ticket::orderBy('created_at', 'desc')->first();

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
		$id = $this->sanitizeString(Input::get('id'));
		$status = 'Open';
		$underrepair = false;
		$details = "";

		if(Input::has('contains'))
		{
			$details = $this->sanitizeString(Input::get('details'));
		}
		else
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
			$maintenanceactivity = App\MaintenanceActivity::find($activity);
			if( count($maintenanceactivity) > 0 )
			{
				$details = isset($maintenanceactivity->activity) ? $maintenanceactivity->activity : $activity;
			}
			else
			{
				Session::flash('error-message','Maintenance Activity not found');
				return redirect()->back();
			}

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
		],App\Ticket::$maintenanceRules);

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
		$ticket = App\Ticket::find($id);
		$ticket->details = $details;
		$ticket->status = $status;
		$ticket->underrepair = $underrepair;
		$ticket->resolve();

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
					'data' => App\Ticket::with('itemprofile')
										->with('user')
										->open()
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
		$ticket = new App\Ticket;
		$ticket->getPcTickets($id);
		if(Request::ajax())
		{

			/*
			|--------------------------------------------------------------------------
			|
			| 	return ticket with pc information
			|
			|--------------------------------------------------------------------------
			|
			*/
			return json_encode([
				'data' => $ticket
			]);
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	return ticket with pc information
		|
		|--------------------------------------------------------------------------
		|
		*/
		return json_encode([
			'data' => $ticket
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
			| 	return ticket with room information
			|
			|--------------------------------------------------------------------------
			|
			*/
			return json_encode([
				'data' => App\Ticket::getRoomTickets($id)
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

		$tag = $this->sanitizeString(Input::get('tag'));

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
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	Check if the tag is equipment
		|
		|--------------------------------------------------------------------------
		|
		*/
		if( count($itemprofile = App\ItemProfile::propertyNumber($tag)->first()) > 0)
		{

			/*
			|--------------------------------------------------------------------------
			|
			| 	Check if the equipment is connected to pc
			|
			|--------------------------------------------------------------------------
			|
			*/
			if(count($pc = App\Pc::isPc($tag)) > 0)
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
			if( count($room = App\Room::location($tag)->first()) > 0 )
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
		$ticket = App\Ticket::orderBy('created_at', 'desc')->first();
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
		],App\Ticket::$maintenanceRules);

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

		if(count($item) > 0)
		{

			foreach($item as $item)
			{

				$ticket = new App\Ticket;
				$ticket->ticketname = $ticketname;
				$ticket->tickettype = $tickettype;
				$ticket->details = $details;
				$ticket->author = $author;
				$this->undermaintenance = $underrepair;
				$ticket->staffassigned = $staffassigned;
				$ticket->status = $status;
				$ticket->generate($tag);
				
			}

		}

		DB::commit();

		Session::flash('success-message','Ticket Generated');
		return redirect('ticket');

	}

}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon;
use Session;
use Validator;
use Auth;
use App;
use DB;
use Illuminate\Http\Request;

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
	public function index(Request $request)
	{

		if($request->ajax())
		{

			// laboratory staff
			$query = App\Ticket::orderBy('date','desc');
			if( Auth::user()->accesslevel == 2 )
			{
				$query = $query->staff(Auth::user()->id);
			}

			if($request->has('type'))
			{
				$query = $query->findByType($this->sanitizeString($request->get('type')));
			} 

			if($request->has('assigned'))
			{
				$assigned = $this->sanitizeString($request->get('assigned'));
			}

			if($request->has('status'))
			{
				$query = $query->findByStatus($request->get('status'));
			}

			
			// laboratory clients
			if( Auth::user()->accesslevel == 3 || Auth::user()->accesslevel == 4  )
			{
				$query = $query->selfAuthored()->selfAssigned()->findByType('Complaint');
			}

			return datatables($query->get())->toJson();
		}

		// total tickets
		$total_tickets = App\Ticket::count();
		$complaints = App\Ticket::findByType('complaint')
						->open()
						->count();

		$author = Auth::user()->firstname." ".Auth::user()->middlename." ".Auth::user()->lastname;
		$authored_tickets = App\Ticket::where('author','=',$author)
										->count();
		$open_tickets = App\Ticket::findByType('complaint')
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
	public function create(Request $request)
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
	public function store(Request $request)
	{
		$tag = $this->sanitizeString($request->get('tag'));
		$type = 'complaint';
		$author = null;
		$staffassigned = null;

		if($request->has('tickettype'))
		{
			$type = 'incident';
		}

		if($request->has('subject'))
		{
			$title = $this->sanitizeString($request->get('subject'));

			if($title == '' || $title == null)
			{
				$title = $type;
			}
		}
		else
		{
			$title = $type;
		}

		if($request->has('author'))
		{
			$author = $this->sanitizeString($request->get('author'));
		}

		$details = $this->sanitizeString($request->get('description'));

		if(Auth::user()->accesslevel <= 2)
		{
			if($request->has('assign-to-staff'))
			{
				$staffassigned = $this->sanitizeString($request->get('staffassigned'));
			}
		}

		$validator = Validator::make([
				'Ticket Subject' => $title,
				'Details' => $details,
				'Author' => $author,
			],App\Ticket::$complaintRules);

		if($validator->fails())
		{
			return redirect('ticket/create')
				->withInput()
				->withErrors($validator);
		}

		DB::beginTransaction();
		/**
		 * find the type in database
		 * if found, return the type information
		 * if not, create a new record
		 */
		$type = App\TicketType::firstOrCreate([
			'name' => ucfirst($type)
		]);

		$ticket = new App\Ticket;
		$ticket->title = $title;
		$ticket->details = $details;
		$ticket->type_id = $type->id;
		$ticket->staff_id = $staffassigned;
		$ticket->status = 'Open';
		$ticket->generate($tag);

		DB::commit();

		Session::flash('success-message','Ticket Generated');
		return redirect('ticket');

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
					$ticket =  App\Ticket::where('parent_id','=',$id)
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
							$id = $ticket->parent_id;
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

			$ticket = App\Ticket::where('id','=',$id)->first();

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
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $request, $id)
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
	public function update(Request $request, $id)
	{
		$propertynumber = $this->sanitizeString($request->get('propertynumber'));
		$type = $this->sanitizeString($request->get('type'));
		$maintenancetype = $this->sanitizeString($request->get('maintenancetype'));
		$category = $this->sanitizeString($request->get('category'));
		$author = $this->sanitizeString($request->get('author'));
		$details = $this->sanitizeString($request->get('description'));
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
	public function transfer(Request $request, $id = null)
	{

		$id = $this->sanitizeString($request->get('id'));
		$staffassigned = $this->sanitizeString($request->get('transferto'));
		$comments = $this->sanitizeString($request->get('comment'));

	
		$validator = Validator::make([
				'Ticket ID' => $id,
				'Staff Assigned' => $staffassigned
			], App\Ticket::$transferRules );

		if($validator->fails())
		{
			Session::flash('error-message','Problem encountered while processing your request');
			return redirect()->back();
		}

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
	public function destroy(Request $request, $id)
	{
		if($request->ajax())
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
	public function reOpenTicket(Request $request, $id)
	{
		if($request->ajax())
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
	
	/**
	*
	*	@return ajax: 'success' or 'error'
	*	normal: view with prompt
	*
	*
	*/
	public function resolve(Request $request)
	{
		/*
		|--------------------------------------------------------------------------
		|
		| 	Intantiate Values
		|
		|--------------------------------------------------------------------------
		|
		*/
		$id = $this->sanitizeString($request->get('id'));
		$status = 'Open';
		$underrepair = false;
		$details = "";

		if($request->has('contains'))
		{
			$details = $this->sanitizeString($request->get('details'));
		}
		else
		{
			
			$activity = $this->sanitizeString($request->get('activity'));
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
		if($request->has('underrepair'))
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
		if($request->has('working'))
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
		if($request->has('close'))
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
	public function complaint(Request $request)
	{
		return redirect('ticket/complaint');
	}

	/**
	*
	*	@return complaint view
	*	@return opened ticket
	*
	*/
	public function complaintViewForStudentAndFaculty(Request $request)
	{
		if($request->ajax())
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
	*	@param $tag
	*	@return item information
	*	@return is existing room
	*	@return pc information
	*
	*/
	public function getTagInformation(Request $request, App\Ticket $ticket)
	{

		$tag = $this->sanitizeString($request->get('id'));

		return response()->json( $ticket->getTagDetails($tag) , 200);
	}

}

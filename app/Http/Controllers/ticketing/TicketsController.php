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
		$type = $request->get('type');
		$status = $request->get('status');

		if($request->ajax())
		{

			$query = App\Ticket::orderBy('date','desc');

			if( Auth::user()->accesslevel == 2 )
			{
				$query = $query->staff(Auth::user()->id);
			}

			if($request->has('type'))
			{
				if( $type != 'all') $query = $query->findByType($this->sanitizeString( $type ));
			}

			if($request->has('assigned'))
			{
				$assigned = $this->sanitizeString($request->get('assigned'));
			}

			if($request->has('status'))
			{
				$query = $query->findByStatus($request->get('status'));
			} else {
				$query = $query->findByStatus( 'Open' );
			}
			
			if( Auth::user()->accesslevel == 3 || Auth::user()->accesslevel == 4  )
			{
				$query = $query->selfAuthored()->findByType('Complaint');
			}

			return datatables($query->get())->toJson();
		}

		// total tickets
		$total_tickets = App\Ticket::count();
		$complaints = App\Ticket::selfAuthored()->findByType('complaint')->open()->count();
		$authored_tickets = App\Ticket::selfAuthored()->count();
		$open_tickets = App\Ticket::selfAuthored()->open()->count();
		$ticket_types = App\TicketType::all();

		return view('ticket.index')
				->with('ticket_types', $ticket_types)
				->with('ticket_statuses',$this->ticket_status)
				->with('lastticket',$total_tickets + 1)
				->with('total_tickets', $total_tickets)
				->with('complaints',$complaints)
				->with('authored_tickets',$authored_tickets)
				->with('open_tickets',$open_tickets)
				->with('type', $type)
				->with('status', $status);
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
					->whereNotIn('id', [ Auth::user()->id, App\User::admin()->first()->id ])
					->select(
						'id as id',
						DB::raw('CONCAT( firstname , " " , middlename , " " , lastname ) as name')
					)
					->pluck('name','id');
		$ticket_types = App\TicketType::pluck('name', 'id');

		return view('ticket.create')
				->with('lastticket',$last_generated_ticket)
				->with('staff',$staff)
				->with('ticket_types', $ticket_types);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$tag = $this->sanitizeString($request->get('tag'));
		$ticket_type = $this->sanitizeString($request->get('ticket_type'));
		$title = $this->sanitizeString($request->get('subject'));
		$author = null;
		$staff = null;

		if($request->has('author')) {
			$author = $this->sanitizeString($request->get('author'));
		}

		$details = $this->sanitizeString($request->get('description'));

		if(Auth::user()->accesslevel <= 2) {
			if($request->has('assign-to-staff')) {
				$staff = $this->sanitizeString($request->get('staffassigned'));
			}
		}

		$validator = Validator::make([
				'Subject' => $title,
				'Details' => $details,
				'Staff' => $staff,
				'Type' => $ticket_type
			],App\Ticket::$rules);

		if($validator->fails()) {
			return redirect('ticket/create')
				->withInput()
				->withErrors($validator);
		}

		DB::beginTransaction();

		$ticket = new App\Ticket;
		$ticket->title = $title;
		$ticket->details = $details;
		$ticket->type_id = $ticket_type;
		$ticket->staff_id = $staff;
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

		$ticket = App\Ticket::where('id','=',$id)->first();
		$last_ticket = App\Ticket::orderBy('created_at', 'desc')->first();

		if($ticket && $ticket->user_id != Auth::user()->id && ( in_array(Auth::user()->accesslevel, [ 3, 4] )) )
		{
			return redirect('ticket')->withErrors(['Invalid Ticket']);
		}

		if ( $last_ticket && $last_ticket->count() == 0 )
		{
			$last_ticket = 1;
		}
		elseif( $last_ticket && $last_ticket->count() > 0 )
		{
			$last_ticket = $last_ticket->id + 1;
		}

		if( !$ticket || $ticket->count() <= 0)
		{
			return redirect('ticket')->withErrors(['Invalid Ticket']);
		}

		return view('ticket.show')
			->with('ticket',$ticket)
			->with('id',$id)
			->with('lastticket',$last_ticket);

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
		$staff_id = $this->sanitizeString($request->get('transferto'));
		$comments = $this->sanitizeString($request->get('comment'));
	
		$validator = Validator::make([
				'Ticket ID' => $id,
				'Staff Assigned' => $staff_id
			], App\Ticket::$transferRules );

		if($validator->fails())
		{
			Session::flash('error-message','Problem encountered while processing your request');
			return redirect()->back();
		}

		$ticket = App\Ticket::find($id);
		$ticket->status = 'Open';
		$ticket->comments = $comments;
		$ticket->staff_id = $staff_id;
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

			if( $ticket && $ticket->count() <= 0) return json_encode('error');
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

			if($ticket && $ticket->count() > 0)
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
		$activity = $this->sanitizeString($request->get('activity'));
		$status = 'Open';
		$underrepair = false;
		$details = "";

		if($request->has('contains'))
		{
			$details = $this->sanitizeString($request->get('details'));
		}
		else
		{
			
			$validator = Validator::make([
				'activity' => $activity
			], App\Activity::$isExistingActivity);

			if( $validator->fails() )
			{
				// Session::flash('error-message', $validator->errors()->first('activity')) ;
				return redirect()->back()->withErrors($validator);
			}

			$suggestions = App\Activity::find($activity);
			$details = isset($suggestions->activity) ? $suggestions->activity : $activity;

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
			// Session::flash('error-message', $validator->errors()->first('Details') );
			return redirect()->back()->withErrors($validator);
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

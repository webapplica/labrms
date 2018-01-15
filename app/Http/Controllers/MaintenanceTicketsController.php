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

class MaintenanceTicketsController extends Controller {

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

			$staff_id = null;
			$type = "";
			$assigned = "";
			$status = "";

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
				$staff_id = Auth::user()->id;
				$query = $query->staff($staff_id);
			}

			if(Input::has('type'))
			{
				$type = $this->sanitizeString(Input::get('type'));
				$query = $query->tickettype($type);
			}

			if(Input::has('assigned'))
			{
				$assigned = $this->sanitizeString(Input::get('assigned'));
			}

			if(Input::has('status'))
			{
				$status = $this->sanitizeString(Input::get('status'));
				$query = $query->status($status);
			}

			if( Auth::user()->accesslevel == 3 || Auth::user()->accesslevel == 4  )
			{
				$query = $query->self()->tickettype('Complaint');
			}

			return json_encode([
				'data' => $query->get()
		 	]);
		}

		$ticket = Ticket::orderBy('created_at', 'desc')->first();

		if (count($ticket) == 0 )
		{
			$ticket = 1;
		}
		else if ( count($ticket) > 0 )
		{
			$ticket = $ticket->id + 1;
		}

		$total_tickets = App\Ticket::count();
		$complaints = App\Ticket::tickettype('complaint')
						->open()
						->count();

		$authored_tickets = App\Ticket::where('author','=',Auth::user()->firstname." ".Auth::user()->middlename." ".Auth::user()->lastname)
						->count();
		$open_tickets = App\Ticket::tickettype('complaint')
						->open()
						->count();

		return view('ticket.maintenance.index')
				->with('tickettype',App\TicketType::all())
				->with('ticketstatus',['Open','Closed'])
				->with('lastticket',$ticket)
				->with('total_tickets',$total_tickets)
				->with('complaints',$complaints)
				->with('authored_tickets',$authored_tickets)
				->with('open_tickets',$open_tickets)
				->with('title','Maintenance Tickets');
	}

	/**
	*
	*	maintenance view
	*
	*/
	public function create()
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

		return view('ticket.maintenance.create')
				->with('lastticket',$ticket)
				->with('activity',$activity)
				->with('title','Maintenance Tickets::create');
	}


	/**
	 * Maintenance function.
	 *
	 * @return Response
	 */
	public function store()
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

	public function show($id)
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

			return view('ticket.maintenance.show')
				->with('ticket',$ticket)
				->with('id',$id)
				->with('lastticket',$lastticket)
				->with('title',$ticket->title);
		}
		catch ( Exception $e )
		{

			Session::flash('error-message','Problem encountered while processing your request');
			return redirect('ticket');

		}
	}

}
